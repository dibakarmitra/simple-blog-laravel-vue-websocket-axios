<template>
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Title</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody v-for="post in posts">
            <tr>
                <th>{{ post.id }}</th>
                <td>
                    <a :href="'/posts/'+post.slug" class="btn btn-sm btn-default">{{ post.title }}</a>
                </td>
                <td>{{ post.published ? "Published" : "Draft" }}</td>
                <td><a :href="'/posts/'+post.slug+'/edit'" class="btn btn-sm btn-default">Edit</a></td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ["page"],
    data() {
        return {
            posts: {},
        };
    },
    created() {
        this.getPosts();
        this.listen();
    },
    methods: {
        getPosts() {
            axios
                .get("/api/posts?page="+this.page)
                .then(response => {
                    this.posts = response.data.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listen() {
            Echo.channel("posts").listen("NewPost", post => {
                this.posts.unshift(comment);
            });
        }
    }
};
</script>
