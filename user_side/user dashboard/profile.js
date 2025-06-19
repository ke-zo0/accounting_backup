document.addEventListener('DOMContentLoaded', function() {
  const infoLink = document.getElementById('infoLink');
  const passwordLink = document.getElementById('passwordLink');
  const infoForm = document.getElementById('infoForm');
  const passwordForm = document.getElementById('passwordForm');
  const backBtn = document.getElementById('backBtn');
  const homeLogo = document.getElementById('homeLogo');

  if (infoLink && passwordLink && infoForm && passwordForm) {
    infoLink.addEventListener('click', function(e) {
      e.preventDefault();
      infoLink.classList.add('active');
      passwordLink.classList.remove('active');
      infoForm.style.display = '';
      passwordForm.style.display = 'none';
    });
    passwordLink.addEventListener('click', function(e) {
      e.preventDefault();
      passwordLink.classList.add('active');
      infoLink.classList.remove('active');
      infoForm.style.display = 'none';
      passwordForm.style.display = '';
    });
  }
  if (backBtn) {
    backBtn.addEventListener('click', function() {
      window.history.back();
    });
  }
  if (homeLogo) {
    homeLogo.addEventListener('click', function() {
      window.location.href = 'userdashboard.php';
    });
  }

  // Password Edit/Save/Cancel logic
  const editBtn = document.getElementById('editPasswordBtn');
  const passwordFields = [
    document.getElementById('currentPassword'),
    document.getElementById('newPassword'),
    document.getElementById('retypePassword')
  ];
  const passwordActions = document.getElementById('passwordActions');

  function setPasswordFieldsDisabled(disabled) {
    passwordFields.forEach(f => f.disabled = disabled);
  }

  function showEditButton() {
    passwordActions.innerHTML = '<button type="button" class="edit-btn" id="editPasswordBtn">Edit</button>';
    document.getElementById('editPasswordBtn').onclick = onEditClick;
    setPasswordFieldsDisabled(true);
  }

  function showSaveCancelButtons() {
    passwordActions.innerHTML = `
      <button type="button" class="cancel-btn" id="cancelPasswordBtn">Cancel</button>
      <button type="submit" class="save-btn" id="savePasswordBtn">Save changes</button>
    `;
    setPasswordFieldsDisabled(false);
    document.getElementById('cancelPasswordBtn').onclick = function(e) {
      e.preventDefault();
      showEditButton();
      passwordFields.forEach(f => f.value = '');
    };
  }

  function onEditClick(e) {
    e.preventDefault();
    showSaveCancelButtons();
  }

  function attachEditListener() {
    const editBtn = document.getElementById('editPasswordBtn');
    if (editBtn) {
      editBtn.onclick = onEditClick;
    }
  }

  // Call this after every time you update passwordActions.innerHTML
  attachEditListener();

  // On form submit, handle password change (AJAX or normal submit)
  document.getElementById('passwordForm').onsubmit = function(e) {
    e.preventDefault();
    const current = passwordFields[0].value.trim();
    const newPass = passwordFields[1].value.trim();
    const retype = passwordFields[2].value.trim();
    const msgDiv = document.getElementById('passwordMessage');
    msgDiv.style.color = 'red';
    msgDiv.textContent = '';
    if (!current || !newPass || !retype) {
      msgDiv.textContent = 'All fields are required.';
      return;
    }
    if (newPass !== retype) {
      msgDiv.textContent = 'New password and retype password do not match.';
      return;
    }
    // AJAX request
    fetch('profile.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=change_password&currentPassword=${encodeURIComponent(current)}&newPassword=${encodeURIComponent(newPass)}&retypePassword=${encodeURIComponent(retype)}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.logout) {
        msgDiv.style.color = 'green';
        msgDiv.textContent = data.message;
        setPasswordFieldsDisabled(true);
        showEditButton();
        passwordFields.forEach(f => f.value = '');
        setTimeout(function() {
          window.location.href = '/finals_integrated/Login/login.php';
        }, 1000);
      } else {
        msgDiv.textContent = data.message;
      }
    })
    .catch(() => {
      msgDiv.textContent = 'An error occurred. Please try again.';
    });
  };

  // Password visibility toggle logic
  document.querySelectorAll('.input-icon .icon.eye').forEach(function(eyeIcon) {
    eyeIcon.addEventListener('click', function() {
      const input = this.previousElementSibling;
      if (input.type === 'password') {
        input.type = 'text';
        this.style.opacity = 1;
      } else {
        input.type = 'password';
        this.style.opacity = 0.7;
      }
    });
  });

  // Info Edit/Save/Cancel logic
  const infoFields = [
    document.getElementById('infoUsername'),
    document.getElementById('infoEmail'),
    document.getElementById('infoPhone')
  ];
  const infoActions = document.getElementById('infoActions');

  function setInfoFieldsDisabled(disabled) {
    infoFields.forEach(f => f.disabled = disabled);
  }

  function showInfoEditButton() {
    infoActions.innerHTML = '<button type="button" class="edit-btn" id="editInfoBtn">Edit</button>';
    document.getElementById('editInfoBtn').onclick = onInfoEditClick;
    setInfoFieldsDisabled(true);
  }

  function showInfoSaveCancelButtons() {
    infoActions.innerHTML = `
      <button type="button" class="cancel-btn" id="cancelInfoBtn">Cancel</button>
      <button type="submit" class="save-btn" id="saveInfoBtn">Save changes</button>
    `;
    setInfoFieldsDisabled(false);
    document.getElementById('cancelInfoBtn').onclick = function(e) {
      e.preventDefault();
      showInfoEditButton();
      // Reset fields to original values
      infoFields[0].value = infoFields[0].getAttribute('value');
      infoFields[1].value = infoFields[1].getAttribute('value');
      infoFields[2].value = infoFields[2].getAttribute('value');
    };
  }

  function onInfoEditClick(e) {
    e.preventDefault();
    showInfoSaveCancelButtons();
  }

  // Attach info edit listener
  showInfoEditButton();

  // Info form submit
  document.getElementById('infoForm').onsubmit = function(e) {
    e.preventDefault();
    const username = infoFields[0].value.trim();
    const email = infoFields[1].value.trim();
    const phone = infoFields[2].value.trim();
    const msgDiv = document.getElementById('infoMessage');
    msgDiv.style.color = 'red';
    msgDiv.textContent = '';
    if (!username || !email || !phone) {
      msgDiv.textContent = 'All fields are required.';
      return;
    }
    fetch('profile.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=update_info&username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}&phone=${encodeURIComponent(phone)}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        msgDiv.style.color = 'green';
        msgDiv.textContent = data.message;
        setInfoFieldsDisabled(true);
        showInfoEditButton();
      } else {
        msgDiv.textContent = data.message;
      }
    })
    .catch(() => {
      msgDiv.textContent = 'An error occurred. Please try again.';
    });
  };
});

