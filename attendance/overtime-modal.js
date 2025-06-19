// Modal logic for Overtime
const openBtn = document.getElementById('openOvertimeModal');
const modal = document.getElementById('overtimeModal');
const closeBtn = document.getElementById('closeOvertimeModal');
const employeeSelect = document.getElementById('employeeSelect');
const rateInput = document.getElementById('ratePerHour');
const positionIdInput = document.getElementById('positionId');

openBtn.onclick = () => { modal.classList.add('active'); };
closeBtn.onclick = () => { modal.classList.remove('active'); };
modal.onclick = e => { if(e.target === modal) modal.classList.remove('active'); };

// Set rate and position ID when employee is selected
employeeSelect.addEventListener('change', function() {
  const selected = this.options[this.selectedIndex];
  rateInput.value = selected.getAttribute('data-rate') || '';
  positionIdInput.value = selected.getAttribute('data-position') || '';
}); 