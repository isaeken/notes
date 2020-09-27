require('./bootstrap')
const $ = require('jquery')
const axios = require('axios')
const config = {
    headers: {
        Authorization: 'Bearer ' + window.Application.api_token,
        Accept: 'application/json'
    }
}

global.createNote = function (title = '', type = 'private', content = '', comments = false, success = () => {}, fail = () => {}, always = () => {}) {
    axios.post('/api/v1/note/store', {
        title: title,
        type: type,
        content: content,
        comments: comments,
    }, config).then(success).catch(fail).then(always)
}

global.updateNote = function (id = 0, title = '', type = 'private', content = '', comments = false, success = () => {}, fail = () => {}, always = () => {}) {
    axios.put('/api/v1/note/update/' + id, {
        title: title,
        type: type,
        content: content,
        comments: comments,
    }, config).then(success).catch(fail).then(always)
}

global.deleteNote = function (id = 0, success = () => {}, fail = () => {}, always = () => {}) {
    axios.delete('/api/v1/note/destroy/' + id, config).then(success).catch(fail).then(always)
}

$(document).ready(function () {
    axios.get('/api/v1/note/', config).then((response) => {
        console.log(response)
    }).catch((error) => {
        console.error(error)
    })
})
