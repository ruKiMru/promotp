const outputToDb = document.querySelector("form"),
    continueBtnSave = outputToDb.querySelector(".add-configuration");

outputToDb.onsubmit = (e) => {
    e.preventDefault()
}

continueBtnSave.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "requests/add/configuration.php", true);
    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                if (response.success === true) {
                    alert(response.message);
                    closeTaskAddIssueWindow()
                } else {
                    alert(response.message);
                }
            }
        }
    }
    let formData = new FormData(outputToDb);
    xhr.send(formData);
}