export function formatRelativeTime(timestamp) {
    const currentDate = new Date();
    const targetDate = new Date(timestamp);
  
    const timeDiff = Math.abs(currentDate - targetDate);
    const seconds = Math.floor(timeDiff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
  
    if (days >= 3) {
        const options = {  day: 'numeric', month: 'numeric', year: 'numeric' };
        return targetDate.toLocaleDateString(undefined, options);
      } else if (days >= 1) {
        return `${days} jour${days > 1 ? 's' : ''}`;
      } else if (hours >= 1) {
        return `${hours} heure${hours > 1 ? 's' : ''}`;
      } else if (minutes >= 1) {
        return `${minutes} minute${minutes > 1 ? 's' : ''}`;
      } else {
        return `à l'instant`;
    }
  }
  