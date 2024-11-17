function post(url, params, success, error) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.onload = () => {
        if (xhr.readyState == 4) {
            console.log(JSON.parse(xhr.response))
            if (xhr.status == 200) {
                if (success)
                    success(JSON.parse(xhr.response))
            } else {
                if (error)
                    error(JSON.parse(xhr.response))
            }
        }
    };
    const formData = new FormData()
    for (var key of Object.keys(params))
        formData.append(key, params[key])
    xhr.send(formData)
}

function string_between_strings(startStr, endStr, str) {
    let pos = str.indexOf(startStr) + startStr.length;
    return str.substring(pos, str.indexOf(endStr, pos));
}


setTimeout(() => {
    let results = []

    const lines = document.querySelectorAll('.entity-result__title-line');
    for (let line of lines) {
        try{
            let parent = line.parentElement.parentElement.parentElement
            let title = parent.querySelectorAll('.entity-result__title-text')[0]
            let titleLink = title.querySelectorAll('a.app-aware-link')[0]
            let linkUrl = new URL(titleLink.href)


            let pro = parent.querySelectorAll('.entity-result__primary-subtitle')[0].innerHTML
            pro = string_between_strings("<!---->", "<!---->", pro)

            let location = parent.querySelectorAll('.entity-result__secondary-subtitle')[0].innerHTML
            location = string_between_strings("<!---->", "<!---->", location)

            results.push({
                id: linkUrl.pathname.replace('/in/', ''),
                title: titleLink.innerText.substring(0, titleLink.innerText.indexOf('\n')),
                profession: pro,
                location: location,
            })
        } catch (e) {
            console.log(e)
        }
    }
    /*const links = document.querySelectorAll('a.app-aware-link');
    const linkedinLinks = Array.from(links).filter(link => link.href.startsWith('https://www.linkedin.com/in'));

    for (let link of linkedinLinks) {
        results.push({
            title: link.innerText,
            href: link.href,
        })
    }*/
    //entity-result__title-text t-16
    //entity-result__primary-subtitle t-14 t-black t-normal
    //entity-result__secondary-subtitle t-14 t-normal
    post("http://localhost/ext/catch.php", {
        html: JSON.stringify(results)
    })
}, 3000);

setTimeout(() => {
  let uri = window.location.search.substring(1)
  let params = new URLSearchParams(uri)
  params.set("page", "" + (parseInt(params.get("page")) + 1))
  window.location.href = window.location.origin + window.location.pathname + "?" + params.toString()
}, 5000);

