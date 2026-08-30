// check if someone actually logged in first, if not send them back to login
const currentUser = localStorage.getItem('loggedInUser');

if (!currentUser) {
    window.location.href = 'index.html';
}

// picks the greeting based on what time it is on the computer's clock
function setGreeting() {
    const hour = new Date().getHours();
    let greetingText = 'Good evening';

    if (hour < 12) {
        greetingText = 'Good morning';
    } else if (hour < 18) {
        greetingText = 'Good afternoon';
    }

    document.getElementById('greeting').innerHTML =
        greetingText + ', <span id="usernameDisplay">' + currentUser + '</span>';
}

function updateStatistics() {
    // fills in the 4 stat cards with sample data
    // just using sample numbers for the exercise
    const stats = [
        { title: 'Steps Today', value: '8,542' },
        { title: 'Calories Burned', value: '2,150 kcal' },
        { title: 'Workouts This Week', value: '4' },
        { title: 'Avg Heart Rate', value: '118 bpm' }
    ];

    for (let i = 0; i < stats.length; i++) {
        document.getElementById('stat' + (i + 1) + '-title').textContent = stats[i].title;
        document.getElementById('stat' + (i + 1) + '-value').textContent = stats[i].value;
    }
}

// builds the workout history table row by row
function populateActivityTable() {
    const activities = [
        { date: 'Aug 28, 2026', workout: 'Morning Run', duration: '32 min', calories: '410', status: 'Completed' },
        { date: 'Aug 27, 2026', workout: 'Upper Body Strength', duration: '45 min', calories: '380', status: 'Completed' },
        { date: 'Aug 26, 2026', workout: 'Yoga and Stretching', duration: '20 min', calories: '110', status: 'Completed' },
        { date: 'Aug 25, 2026', workout: 'HIIT Cardio', duration: '25 min', calories: '320', status: 'Missed' }
    ];

    const tableBody = document.getElementById('activityTableBody');
    tableBody.innerHTML = '';

    activities.forEach(function (activity) {
        // green badge if completed, red badge if missed
        const badgeClass = activity.status === 'Completed' ? 'badge-success-custom' : 'badge-danger-custom';

        const row = '<tr>' +
            '<td>' + activity.date + '</td>' +
            '<td>' + activity.workout + '</td>' +
            '<td>' + activity.duration + '</td>' +
            '<td>' + activity.calories + ' kcal</td>' +
            '<td><span class="badge ' + badgeClass + '">' + activity.status + '</span></td>' +
            '</tr>';

        tableBody.insertAdjacentHTML('beforeend', row);
    });
}
// clears the saved login and sends the user back to the login page
document.getElementById('logoutBtn').addEventListener('click', function () {
    localStorage.removeItem('loggedInUser');
    window.location.href = 'index.html';
});

// only run all of this stuff if we know someone's actually logged in
if (currentUser) {
    document.getElementById('usernameDisplay').textContent = currentUser;
    setGreeting();
    updateStatistics();
    populateActivityTable();
}