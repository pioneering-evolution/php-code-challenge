<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>
                    <table v-for="(performer) in performers">
                        <tr>
                            <th> {{performer.display_name}} </th>
                            <th> Non Labor </th>
                            <th> Labor </th>
                            <th> Total </th>
                        </tr>

                        <tr v-for='task in performerTask(performer.id)' >
                            <td colspan="40%">{{ task.item_title }} - {{task.year}}</td>
                            <td colspan="10%" v-if="task.type == 'non_labor'">{{task.amount}}</td>
                            <td colspan="10%" v-else>0.00</td>
                            <td colspan="10%" v-if="task.type == 'labor'">{{task.amount}}</td>
                            <td colspan="10%" v-else>0.00</td>

                            <td colspan="10%">total coming</td>

                        </tr>
                    </table>

                    <div class="card-body">
                        I'm an example component.

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            info: null,
            performers: [],
            test: null,
            labor_groups: null,
            non_labor_groups: null,
            tasks: [],
            loading: true,
            errored: false
        }
    },
    mounted() {
        this.loadPerformer();
        this.loadTasks();
    },

    methods:
        {
            loadPerformer: function () {
                axios
                    .get('api/performers/')
                    .then(response => {
                        this.performers = response.data
                        console.log(this.performers)
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
                    .finally(() => this.loading = false)
            },
            loadTasks: function () {
                axios
                    .get('api/tasks/')
                    .then(response => {
                        this.tasks = response.data
                        console.log(this.tasks)
                    })
                    .catch(error => {
                        console.log(error)
                        this.errored = true
                    })
                    .finally(() => this.loading = false)
            },
            performerTask(owner) {
                return this.tasks.filter(task => task.owner === owner)
            }
        }
}
</script>
