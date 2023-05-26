localStorage.setItem('like', 'like');

export function setItem(id) {
    localStorage.setItem('like-'+id, id);
}
export function getItem(id) {
    return localStorage.getItem('like-'+id);
}
export function unsetItem(id) {
    localStorage.removeItem('like-'+id);
}