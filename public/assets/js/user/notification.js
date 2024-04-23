function close_notification() {
    document.querySelector('.notification').style.display="none";
}

document.addEventListener("DOMContentLoaded", function() {
    const notification = document.querySelector('.notification');

    document.addEventListener('click', function(event) {
      if (!notification.contains(event.target)) {
        notification.style.display = 'none';
      }
    });
  });