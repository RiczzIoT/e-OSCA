// Begin custom key combinations for redirecting to settings pages
async function hashPasscode(passcode) {
  const encoder = new TextEncoder();
  const data = encoder.encode(passcode);
  const hash = await crypto.subtle.digest('SHA-256', data);
  return Array.from(new Uint8Array(hash)).map(b => b.toString(16).padStart(2, '0')).join('');
}

document.addEventListener('keydown', async function(event) {
  const keyCombination9 = event.ctrlKey && event.altKey && event.key === '9';
  const keyCombination8 = event.ctrlKey && event.altKey && event.key === '2';
  const keyCombination7 = event.ctrlKey && event.altKey && event.key === '3';

  const correctHash = 'af3bb3abe71a59adb0761033147dc32e63470fe232525aeb513b487fcbf4e0b9'; // Hash for '8923149'

  if (keyCombination9) {
    const passcode = prompt('Please enter the passcode:');
    const hashedPasscode = await hashPasscode(passcode);
    if (hashedPasscode === correctHash) {
      window.location.href = '../Settings/?id=401289736548';
    } else {
      alert('Incorrect passcode');
    }
  }

  if (keyCombination8) {
    const passcode = prompt('Please enter the passcode:');
    const hashedPasscode = await hashPasscode(passcode);
    if (hashedPasscode === correctHash) {
      window.location.href = 'http://localhost/one/settings/captain.php?id=1';
    } else {
      alert('Incorrect passcode');
    }
  }

  if (keyCombination7) {
    const passcode = prompt('Please enter the passcode:');
    const hashedPasscode = await hashPasscode(passcode);
    if (hashedPasscode === correctHash) {
      window.location.href = 'http://localhost/one/settings/staff.php?id=3';
    } else {
      alert('Incorrect passcode');
    }
  }
});

// End custom key combinations for redirecting to settings pages