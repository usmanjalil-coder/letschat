function showToast(message, type) {
    const toast = document.createElement('div');
    toast.classList.add('toast');
    toast.textContent = message;
  
    if (type === 'success') {
      toast.classList.add('success');
    } else if (type === 'error') {
      toast.classList.add('error');
    }
  
    document.body.appendChild(toast);
  
    setTimeout(() => {
      toast.classList.add('show');
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
          document.body.removeChild(toast);
        }, 300);
      }, 4000);
    }, 100);
  }