// Add Employee Modal Logic
const addBtn = document.getElementById('addEmployeeBtn');
const modal = document.getElementById('employeeModal');
const cancelBtn = document.getElementById('cancelEmployeeBtn');
addBtn.onclick = () => { modal.classList.add('active'); };
cancelBtn.onclick = () => { modal.classList.remove('active'); };
// Optional: Hide modal on overlay click
modal.onclick = e => { if(e.target === modal) modal.classList.remove('active'); };

// Edit Employee Modal Logic
const editBtns = document.querySelectorAll('.edit-btn');
const editModal = document.getElementById('editEmployeeModal');
const cancelEditBtn = document.getElementById('cancelEditEmployeeBtn');
editBtns.forEach(btn => {
  btn.onclick = function() {
    document.getElementById('edit_id').value = btn.getAttribute('data-id');
    document.getElementById('edit_fname').value = btn.getAttribute('data-fname');
    document.getElementById('edit_mname').value = btn.getAttribute('data-mname');
    document.getElementById('edit_lname').value = btn.getAttribute('data-lname');
    document.getElementById('edit_address').value = btn.getAttribute('data-address');
    document.getElementById('edit_email').value = btn.getAttribute('data-email');
    document.getElementById('edit_phone').value = btn.getAttribute('data-phone');
    document.getElementById('edit_position').value = btn.getAttribute('data-position');
    document.getElementById('edit_join_date').value = btn.getAttribute('data-datehired');
    if(btn.getAttribute('data-sex') === 'Female') {
      document.getElementById('edit_sex_female').checked = true;
    } else if(btn.getAttribute('data-sex') === 'Male') {
      document.getElementById('edit_sex_male').checked = true;
    } else {
      document.getElementById('edit_sex_female').checked = false;
      document.getElementById('edit_sex_male').checked = false;
    }
    // Set schedule dropdown for edit
    const posId = btn.getAttribute('data-position');
    const schedSelect = document.getElementById('edit_schedule');
    const currentScheduleId = btn.getAttribute('data-schedule_id');
    populateScheduleDropdown(posId, schedSelect, currentScheduleId);
    editModal.classList.add('active');
  }
});
document.getElementById('edit_position').addEventListener('change', function() {
  const posId = this.value;
  const schedSelect = document.getElementById('edit_schedule');
  populateScheduleDropdown(posId, schedSelect);
});
cancelEditBtn.onclick = () => { editModal.classList.remove('active'); };
editModal.onclick = e => { if(e.target === editModal) editModal.classList.remove('active'); };

// Add Modal Schedule Dropdown Logic
const positionSelect = document.getElementById('positionSelect');
if (positionSelect) {
  positionSelect.addEventListener('change', function() {
    const posId = this.value;
    const schedSelect = document.getElementById('scheduleSelect');
    populateScheduleDropdown(posId, schedSelect);
  });
}

function populateScheduleDropdown(positionId, dropdown, selectedScheduleId = null) {
    dropdown.innerHTML = '<option value="" disabled selected>Select a schedule</option>';
    if (!positionId) return;
    const filtered = window.allSchedules.filter(s => s.Position_ID == positionId);
    filtered.forEach(s => {
        // Format: 6:00AM-2:00PM (FirstTime_IN - SecondTime_OUT)
        const label = `${s.FirstTime_IN} - ${s.SecondTime_OUT}`;
        const option = document.createElement('option');
        option.value = s.Schedule_ID;
        option.textContent = label;
        if (selectedScheduleId && s.Schedule_ID == selectedScheduleId) {
            option.selected = true;
        }
        dropdown.appendChild(option);
    });
}

const editPhoneInput = document.getElementById('edit_phone');
if (editPhoneInput) {
  editPhoneInput.addEventListener('invalid', function() {
    this.setCustomValidity('Please enter a valid numeric value');
  });
  editPhoneInput.addEventListener('input', function() {
    this.setCustomValidity('');
  });
} 