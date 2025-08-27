// Begin custom key combinations for redirecting to settings pages
async function hashPasscode(passcode) {
  const encoder = new TextEncoder();
  const data = encoder.encode(passcode);
  const hash = await crypto.subtle.digest('SHA-256', data);
  return Array.from(new Uint8Array(hash)).map(b => b.toString(16).padStart(2, '0')).join('');
}

document.addEventListener('keydown', async function(event) {
  const keyCombination9 = event.ctrlKey && event.key === 'z';

  const correctHash = 'af3bb3abe71a59adb0761033147dc32e63470fe232525aeb513b487fcbf4e0b9'; // Hash for '8923149'

  if (keyCombination9) {
    const passcode = prompt('Please enter the passcode:');
    const hashedPasscode = await hashPasscode(passcode);
    if (hashedPasscode === correctHash) {
      window.location.href = '../../users/logs/delete.php';
    } else {
      alert('Incorrect passcode');
    }
  }
});

// End custom key combinations for redirecting to settings pages