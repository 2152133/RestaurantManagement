<template>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li v-bind:class="[{disabled: !links.prev}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getObjects(links.prev)">Previous</a></li>
            
            <li class="page-item disabled"><a class="page-link" href="#">Page {{ meta.current_page }} of {{ meta.last_page }}</a></li>

            <li v-bind:class="[{disabled: !links.next}]" 
            class="page-item"><a class="page-link" href="#"
            @click="getObjects(links.next)">Next</a></li>
        </ul>
    </nav>
</template>

<script>
    module.exports = {
        props: ["objects", "meta", "links"],
        data: function() {
            return {

            }
        }
        ,
        methods: {
            getObjects: function(url) {
                axios.get(url)
                .then(response => {
                    //this.objects = response.data.data;
                    Object.assign(this.objects, response.data.data);
                    this.makePagination(response.data.meta, response.data.links);
                    this.$emit('refreshObjects', this.objects, this.meta, this.links);
                })
                .catch(error => console.log(error))
            },
            makePagination: function(meta, links) {
                this.meta = meta;
                this.links = links;
            },
        },
    };
</script>
