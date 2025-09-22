// SMC Tech Labs - Gamification Engine
class SMCGaming {
    constructor() {
        this.userId = null;
        this.currentChallenge = null;
        this.startTime = null;
        this.points = 0;
        this.streak = 0;
        this.level = 1;
        this.xp = 0;
        this.achievements = [];
        this.sounds = {};
        this.loadSounds();
        this.initializeUI();
    }

    // Initialize gaming interface
    initializeUI() {
        this.createGameHUD();
        this.createAchievementSystem();
        this.createSoundToggle();
        this.loadPlayerData();
    }

    // Create heads-up display
    createGameHUD() {
        const hud = document.createElement('div');
        hud.id = 'game-hud';
        hud.innerHTML = `
            <div class="hud-container">
                <div class="player-info">
                    <div class="level-badge">
                        <span class="level-number">${this.level}</span>
                        <span class="level-label">LVL</span>
                    </div>
                    <div class="xp-bar">
                        <div class="xp-fill" style="width: ${this.getXPPercentage()}%"></div>
                        <span class="xp-text">${this.xp} XP</span>
                    </div>
                    <div class="rank-display">${this.getRank()}</div>
                </div>
                <div class="game-stats">
                    <div class="stat-item">
                        <span class="stat-icon">🎯</span>
                        <span class="stat-value" id="current-points">${this.points}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-icon">🔥</span>
                        <span class="stat-value" id="current-streak">${this.streak}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-icon">🏆</span>
                        <span class="stat-value" id="achievement-count">${this.achievements.length}</span>
                    </div>
                </div>
                <div class="challenge-timer" id="challenge-timer" style="display: none;">
                    <span class="timer-icon">⏱️</span>
                    <span class="timer-value" id="timer-display">00:00</span>
                </div>
            </div>
        `;
        document.body.appendChild(hud);
    }

    // Achievement notification system
    createAchievementSystem() {
        const container = document.createElement('div');
        container.id = 'achievement-notifications';
        document.body.appendChild(container);
    }

    // Sound toggle control
    createSoundToggle() {
        const toggle = document.createElement('div');
        toggle.id = 'sound-toggle';
        toggle.innerHTML = `
            <button onclick="gaming.toggleSound()" class="sound-btn">
                <span id="sound-icon">🔊</span>
            </button>
        `;
        document.body.appendChild(toggle);
    }

    // Load sound effects
    loadSounds() {
        this.sounds = {
            success: this.createBeep(800, 100),
            error: this.createBeep(200, 200),
            achievement: this.createBeep([600, 800, 1000], 300),
            levelUp: this.createBeep([400, 600, 800, 1000], 500),
            tick: this.createBeep(400, 50),
            challenge: this.createBeep([800, 600], 150)
        };
        this.soundEnabled = localStorage.getItem('smc-sound') !== 'false';
    }

    // Simple beep generator
    createBeep(frequency, duration) {
        return () => {
            if (!this.soundEnabled) return;
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const frequencies = Array.isArray(frequency) ? frequency : [frequency];

            frequencies.forEach((freq, index) => {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = freq;
                oscillator.type = 'sine';

                const startTime = audioContext.currentTime + (index * 0.1);
                const endTime = startTime + (duration / 1000);

                gainNode.gain.setValueAtTime(0.1, startTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, endTime);

                oscillator.start(startTime);
                oscillator.stop(endTime);
            });
        };
    }

    // Toggle sound on/off
    toggleSound() {
        this.soundEnabled = !this.soundEnabled;
        localStorage.setItem('smc-sound', this.soundEnabled);
        document.getElementById('sound-icon').textContent = this.soundEnabled ? '🔊' : '🔇';
        if (this.soundEnabled) this.sounds.tick();
    }

    // Start a challenge
    startChallenge(challengeId, timeLimit = null) {
        this.currentChallenge = challengeId;
        this.startTime = Date.now();
        this.sounds.challenge();

        if (timeLimit) {
            this.startTimer(timeLimit);
        }

        // Log challenge start
        this.logChallengeAttempt(challengeId, 'started');

        // Show challenge UI
        this.showChallengeUI(challengeId);
    }

    // Start countdown timer
    startTimer(seconds) {
        const timerDisplay = document.getElementById('challenge-timer');
        const timerValue = document.getElementById('timer-display');
        timerDisplay.style.display = 'block';

        let remaining = seconds;
        const interval = setInterval(() => {
            const minutes = Math.floor(remaining / 60);
            const secs = remaining % 60;
            timerValue.textContent = `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;

            if (remaining <= 10 && remaining > 0) {
                timerDisplay.classList.add('timer-warning');
                this.sounds.tick();
            }

            if (remaining <= 0) {
                clearInterval(interval);
                timerDisplay.style.display = 'none';
                timerDisplay.classList.remove('timer-warning');
                this.timeUpChallenge();
            }

            remaining--;
        }, 1000);

        this.currentTimer = interval;
    }

    // Complete a challenge
    completeChallenge(success = true, customPoints = null) {
        if (!this.currentChallenge) return;

        const endTime = Date.now();
        const timeTaken = Math.floor((endTime - this.startTime) / 1000);

        // Clear timer if running
        if (this.currentTimer) {
            clearInterval(this.currentTimer);
            document.getElementById('challenge-timer').style.display = 'none';
        }

        if (success) {
            const earnedPoints = customPoints || this.calculatePoints(timeTaken);
            this.awardPoints(earnedPoints);
            this.sounds.success();
            this.showSuccessAnimation(earnedPoints);

            // Check for achievements
            this.checkAchievements();

            // Update streak
            this.streak++;
            this.updateDisplay();
        } else {
            this.sounds.error();
            this.showFailureAnimation();
            this.streak = 0;
        }

        // Log completion
        this.logChallengeAttempt(this.currentChallenge, success ? 'completed' : 'failed', {
            timeTaken,
            pointsEarned: success ? (customPoints || this.calculatePoints(timeTaken)) : 0
        });

        this.currentChallenge = null;
        this.startTime = null;
    }

    // Time's up handler
    timeUpChallenge() {
        this.sounds.error();
        this.showTimeUpAnimation();
        this.completeChallenge(false);
    }

    // Calculate points based on performance
    calculatePoints(timeTaken) {
        const basePoints = 100;
        const timeBonus = Math.max(0, 50 - timeTaken); // Bonus for speed
        const streakMultiplier = 1 + (this.streak * 0.1); // 10% per streak

        return Math.floor((basePoints + timeBonus) * streakMultiplier);
    }

    // Award points and update XP
    awardPoints(points) {
        this.points += points;
        this.xp += points;

        // Check for level up
        const newLevel = this.calculateLevel(this.xp);
        if (newLevel > this.level) {
            this.levelUp(newLevel);
        }

        this.updateDisplay();
        this.savePlayerData();
    }

    // Level up system
    levelUp(newLevel) {
        const oldLevel = this.level;
        this.level = newLevel;
        this.sounds.levelUp();
        this.showLevelUpAnimation(oldLevel, newLevel);

        // Unlock new content based on level
        this.checkLevelUnlocks(newLevel);
    }

    // Calculate level from XP
    calculateLevel(xp) {
        return Math.floor(Math.sqrt(xp / 100)) + 1;
    }

    // Get XP percentage for current level
    getXPPercentage() {
        const currentLevelXP = Math.pow(this.level - 1, 2) * 100;
        const nextLevelXP = Math.pow(this.level, 2) * 100;
        const progress = (this.xp - currentLevelXP) / (nextLevelXP - currentLevelXP);
        return Math.min(100, progress * 100);
    }

    // Get player rank based on level
    getRank() {
        if (this.level >= 50) return 'SMC Lab Director';
        if (this.level >= 25) return 'Tech Lead';
        if (this.level >= 15) return 'Senior Engineer';
        if (this.level >= 8) return 'System Administrator';
        return 'Junior Technician';
    }

    // Update HUD display
    updateDisplay() {
        document.getElementById('current-points').textContent = this.points;
        document.getElementById('current-streak').textContent = this.streak;
        document.getElementById('achievement-count').textContent = this.achievements.length;

        // Update XP bar
        const xpBar = document.querySelector('.xp-fill');
        const xpText = document.querySelector('.xp-text');
        if (xpBar) {
            xpBar.style.width = this.getXPPercentage() + '%';
            xpText.textContent = `${this.xp} XP`;
        }

        // Update level
        const levelNumber = document.querySelector('.level-number');
        const rankDisplay = document.querySelector('.rank-display');
        if (levelNumber) levelNumber.textContent = this.level;
        if (rankDisplay) rankDisplay.textContent = this.getRank();
    }

    // Achievement system
    checkAchievements() {
        // This would typically check against database conditions
        // For now, implement some basic client-side achievements

        if (this.streak === 5 && !this.hasAchievement('streak_5')) {
            this.unlockAchievement('streak_5', 'Consistent Player', '📅', 150);
        }

        if (this.level === 5 && !this.hasAchievement('level_5')) {
            this.unlockAchievement('level_5', 'Rising Star', '⭐', 200);
        }
    }

    // Check if player has achievement
    hasAchievement(code) {
        return this.achievements.some(achievement => achievement.code === code);
    }

    // Unlock achievement
    unlockAchievement(code, name, icon, points) {
        if (this.hasAchievement(code)) return;

        const achievement = { code, name, icon, points };
        this.achievements.push(achievement);
        this.awardPoints(points);
        this.showAchievementUnlock(achievement);
        this.sounds.achievement();
    }

    // Animation systems
    showSuccessAnimation(points) {
        this.createFloatingText(`+${points} XP`, 'success-animation');
    }

    showFailureAnimation() {
        this.createFloatingText('Challenge Failed', 'failure-animation');
    }

    showTimeUpAnimation() {
        this.createFloatingText("Time's Up!", 'timeout-animation');
    }

    showLevelUpAnimation(oldLevel, newLevel) {
        this.createFloatingText(`Level ${newLevel}!`, 'levelup-animation');

        // Screen flash effect
        const flash = document.createElement('div');
        flash.className = 'level-flash';
        document.body.appendChild(flash);
        setTimeout(() => flash.remove(), 500);
    }

    showAchievementUnlock(achievement) {
        const notification = document.createElement('div');
        notification.className = 'achievement-notification';
        notification.innerHTML = `
            <div class="achievement-content">
                <div class="achievement-icon">${achievement.icon}</div>
                <div class="achievement-text">
                    <div class="achievement-title">Achievement Unlocked!</div>
                    <div class="achievement-name">${achievement.name}</div>
                    <div class="achievement-points">+${achievement.points} XP</div>
                </div>
            </div>
        `;

        document.getElementById('achievement-notifications').appendChild(notification);

        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }

    createFloatingText(text, className) {
        const element = document.createElement('div');
        element.className = `floating-text ${className}`;
        element.textContent = text;
        element.style.position = 'fixed';
        element.style.top = '50%';
        element.style.left = '50%';
        element.style.transform = 'translate(-50%, -50%)';
        element.style.zIndex = '10000';
        element.style.pointerEvents = 'none';

        document.body.appendChild(element);

        setTimeout(() => element.remove(), 2000);
    }

    // Challenge UI management
    showChallengeUI(challengeId) {
        // This would be implemented per lab with specific challenge interfaces
        console.log(`Starting challenge: ${challengeId}`);
    }

    // Data persistence
    savePlayerData() {
        const data = {
            points: this.points,
            xp: this.xp,
            level: this.level,
            streak: this.streak,
            achievements: this.achievements
        };
        localStorage.setItem('smc-player-data', JSON.stringify(data));
    }

    loadPlayerData() {
        const saved = localStorage.getItem('smc-player-data');
        if (saved) {
            const data = JSON.parse(saved);
            this.points = data.points || 0;
            this.xp = data.xp || 0;
            this.level = data.level || 1;
            this.streak = data.streak || 0;
            this.achievements = data.achievements || [];
        }
        this.updateDisplay();
    }

    // Server communication
    async logChallengeAttempt(challengeId, status, data = {}) {
        try {
            const response = await fetch('/api/log-challenge.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    challengeId,
                    status,
                    ...data
                })
            });
        } catch (error) {
            console.log('Offline mode - data saved locally');
        }
    }

    // Utility functions
    checkLevelUnlocks(level) {
        // Implement level-based feature unlocks
        if (level === 5) {
            this.showNotification('New challenges unlocked!');
        }
    }

    showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'game-notification';
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Challenge helper methods
    addHint(cost = 15) {
        if (this.points >= cost) {
            this.points -= cost;
            this.updateDisplay();
            this.savePlayerData();
            return true;
        }
        return false;
    }

    useSpeedBoost(cost = 25) {
        if (this.points >= cost) {
            this.points -= cost;
            this.updateDisplay();
            this.savePlayerData();
            return true;
        }
        return false;
    }
}

// Initialize gaming system
window.gaming = new SMCGaming();