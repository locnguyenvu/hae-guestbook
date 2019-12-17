var formSubmitComment = {
    data: function() {
        return {
            username: '',
            comment: '',
            responseStatus: 0
        }
    },
    computed: {
        requestSucccess: function() {
            return this.responseStatus == 200;
        }
    },
    methods: {
        submit: function() {
            Hae.createComment(this.username, this.comment)
            .then(response => {
                this.responseStatus = response.status
                this.clear()
                this.$emit('reload-comment-list', true)
            })
        },
        clear: function() {
            this.username = ''
            this.comment = ''
        }
    },
    template: `
        <form>
            <fieldset>
                <legend>Comment</legend>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <label for="username">Username</label>
                        <input type="text" name="username" v-model="username" required="true" />
                    </div>
                    <div class="col-sm-12 col-md-12">
                        <label for="username">Comment</label>
                        <textarea name="comment" v-model="comment" required="true"></textarea>
                    </div>
                </div>
            <fieldset>
            <button type="button" v-on:click="submit">Submit</button>
            <span v-if="requestSucccess" style="color:green;font-weight=400;">Bạn đã comment thành công</span>
        </form>
    `
}

new Vue({
    el: "#app",
    components: {
        'form-submit-comment': formSubmitComment
    },
    data: {
        listGuestbook: []
    },
    created: function() {
        this.loadCommentList()
    },
    methods: {
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