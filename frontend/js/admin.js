var formEditComment = {
    props: {
        comment: Object,
        token: String
    },
    methods: {
        deleteComment: function() {
            Hae.deleteComment(this.comment.id, this.token)
            .then(response => {
                this.$emit('reload-comment-list', true)
            })
        },
        editComment: function() {
            Hae.editComment(this.comment.id, this.comment.content, this.token)
            .then(response => {
                this.$emit('reload-comment-list', true)
            })
        }
    },
    template: `
        <div class="row">
            <div class="col-md-3">
                <span>#{{comment.id}}</span><br><b>{{comment.username}}</b>
            </div>
            <div class="col-md-6">
                <textarea v-model="comment.content" style="width:100%"></textarea>
            </div>
            <div class="col-md-3">
                [<a href="#" style="color:blue" v-on:click="editComment">Save</a>]
                [<a href="#" style="color:red" v-on:click="deleteComment">Delete</a>]
            </div>
        </div>
    `
}

new Vue({
    el: "#app",
    components: {
        'form-edit-comment': formEditComment
    },
    data: {
        loginUsername: "",
        loginPassword: "",
        loginFailed: false,
        token: "",
        listGuestbook: []
    },
    computed: {
        isLogin: function() {
            return this.token != ""
        }
    },
    methods: {
        login: function() {
            Hae.login(this.loginUsername, this.loginPassword)
            .then(response => {
                this.token = response.data.token
                this.loginFailed = false
                this.loadCommentList()
            })
            .catch(error => {
                console.log(error);
                this.loginFailed = true
            })
        },
        loadCommentList: function()
        {
            Hae.listGuestboook()
            .then(response => {
                const requestBody = response.data
                this.listGuestbook = requestBody.data
            })
            .catch(error => {
                console.log(er)
            })
        }
    }
})