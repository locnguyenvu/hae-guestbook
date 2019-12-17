const Hae = {
    http: axios.create({
        baseURL: 'http://api.hae.local',
        timeout: 1000
    }),
    listGuestboook: function() {
        return this.http.get('/guestbook');
    },
    createComment: function(name, comment) {
        return this.http.post('/guestbook', {
            username: name,
            content: comment
        })
    },
    login: function(username, password) {
        return this.http.post('/login', {
            "username": username,
            "password": password
        })
    },
    deleteComment: function(id, token) {
        return this.http.delete('/guestbook/' + id, {
            headers: {
                'Authorization': 'Basic ' + token
            }
        })
    },
    editComment: function(id, content, token) {
        return this.http.put('/guestbook/' + id, {
            'content': content
        },{
            headers: {
                'Authorization': 'Basic ' + token
            }
        })
    }
}