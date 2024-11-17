document.getElementById('saveButton').addEventListener('click', () => {
    console.log("Button clicked!")
    chrome.runtime.sendMessage({action: 'saveHTML'});
});