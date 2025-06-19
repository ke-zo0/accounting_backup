function updateDateTime() {
  const now = new Date();
  const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
  ];
  const day = days[now.getDay()];
  const month = months[now.getMonth()];
  const date = now.getDate();
  const year = now.getFullYear();
  let hour = now.getHours();
  const min = now.getMinutes().toString().padStart(2, '0');
  const sec = now.getSeconds().toString().padStart(2, '0');
  const ampm = hour >= 12 ? 'PM' : 'AM';
  hour = hour % 12;
  hour = hour ? hour : 12; // the hour '0' should be '12'
  const timeString = `${hour}:${min}:${sec} ${ampm}`;
  const dateString = `${day} - ${month} ${date}, ${year}`;
  document.getElementById('datetimeDisplay').innerHTML = `${dateString}<br>${timeString}`;
}

function determineTimeSlot() {
  const now = new Date();
  const hour = now.getHours();
  return hour < 12 ? 'AM' : 'PM';
}

async function handleTimeIn() {
  const timeSlot = determineTimeSlot();
  const currentTime = new Date().toLocaleTimeString('en-US', { hour12: false });
  
  try {
    const response = await fetch('timeinout.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=timein&employeeId=${employeeId}&timeSlot=${timeSlot}&currentTime=${currentTime}`
    });
    
    const data = await response.json();
    document.getElementById('statusMessage').textContent = data.message;
    
    if (data.success) {
      document.getElementById('timeInBtn').disabled = true;
      document.getElementById('timeOutBtn').disabled = false;
    }
  } catch (error) {
    document.getElementById('statusMessage').textContent = 'Error processing time in';
  }
}

async function handleTimeOut() {
  const timeSlot = determineTimeSlot();
  const currentTime = new Date().toLocaleTimeString('en-US', { hour12: false });
  
  try {
    const response = await fetch('timeinout.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=timeout&employeeId=${employeeId}&timeSlot=${timeSlot}&currentTime=${currentTime}`
    });
    
    const data = await response.json();
    document.getElementById('statusMessage').textContent = data.message;
    
    if (data.success) {
      document.getElementById('timeOutBtn').disabled = true;
    }
  } catch (error) {
    document.getElementById('statusMessage').textContent = 'Error processing time out';
  }
}

document.addEventListener('DOMContentLoaded', function() {
  setInterval(updateDateTime, 1000);
  updateDateTime();
  
  document.getElementById('timeInBtn').addEventListener('click', handleTimeIn);
  document.getElementById('timeOutBtn').addEventListener('click', handleTimeOut);
});

document.addEventListener('DOMContentLoaded', function() {
  var backBtn = document.getElementById('backBtn');
  if (backBtn) {
    backBtn.onclick = function() {
      window.history.back();
    };
  }
}); 