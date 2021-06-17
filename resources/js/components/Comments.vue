<template>
    <div class="row-12">
        <h3>Comments:</h3>
        <div style="margin-bottom:50px;" v-if="userid">
            <textarea
                class="form-control"
                rows="3"
                name="body"
                placeholder="Leave a comment"
                v-model="newComment"
                @keyup.enter="postComment"
            >
            </textarea>
            <button
                class="btn btn-success"
                style="margin-top:10px"
                @click.prevent="postComment"
            >
                Save Comment
            </button>
        </div>
        <div v-else>
            <h4>You must be logged in to submit a comment!</h4>
            <a href="/login">Login Now &gt;&gt;</a>
        </div>
        <div class="media" style="margin-top:20px;" v-for="comment in comments">
            <div class="media-left">
                <a href="#">
                    <img
                        class="media-object"
                        src="http://placeimg.com/80/80"
                        alt="..."
                    />
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading">{{ comment.user.name }} said...</h4>
                <p>{{ comment.body }}</p>
                <span style="color: #aaa;">{{ comment.user.created_at }} on </span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["userid", "postid"],
    data() {
        return {
            comments: {},
            newComment: "",
            post: this.postid,
            user: this.userid,
        };
    },
    created() {
        this.getComments();
        this.listen();
    },
    methods: {
        getComments() {
            axios
                .get("/api/posts/" + this.post + "/comments")
                .then(response => {
                    this.comments = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        postComment() {
            axios
                .post("/api/posts/" + this.post + "/comment", {
                    body: this.newComment,
                    user_id: this.user,
                    post_id: this.post,
                })
                .then(response => {
                    this.comments.unshift(response.data);
                    this.newComment = "";
                })
                .catch(error => {
                    console.log(error);
                });
        },
        listen() {
            Echo.private("post." + this.post).listen("NewComment", comment => {
                this.comments.unshift(comment);
            });
        }
    }
};
</script>
