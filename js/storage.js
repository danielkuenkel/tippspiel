function setLocalItem(id, data) {
    sessionStorage.setItem(id, JSON.stringify(data));
}
function getLocalItem(id) {
    if (sessionStorage.getItem(id) !== null) {
        return JSON.parse(sessionStorage.getItem(id));
    } else {
        return null;
    }
}
function removeLocalItem(id) {
    sessionStorage.removeItem(id);
}
function clearLocalItems() {
    sessionStorage.clear();
}