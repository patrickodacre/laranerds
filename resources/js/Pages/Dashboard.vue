<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { reactive, onMounted } from 'vue'

const props = defineProps({
    tasks: Array,
    user: Object,
});

const state = reactive({
    task: newTask(),
    editing_task: null,
    deleting_task: null,
    tasks: [],

    modal_task_form: null,
    modal_task_delete_confirm: null,
})


onMounted(() => {

    state.tasks = JSON.parse(JSON.stringify(props.tasks))

    state.modal_task_delete_confirm = new window.bootstrap.Modal('#modal_task_delete_confirm', {})
    state.modal_task_delete_confirm._element.addEventListener('hide.bs.modal', () => {})


    state.modal_task_form = new window.bootstrap.Modal('#modal_task_form', {})
    state.modal_task_form._element.addEventListener('hide.bs.modal', () => {})
})

function newTask()
{
    return {
        title: null,
        description: null
    }
}

function newTask_init()
{
    state.task = newTask()
    state.editing_task = null
    state.modal_task_form.show()
}

function saveTask()
{

    if (state.editing_task && state.editing_task.id)
    {
        return axios.patch(`/tasks/${state.editing_task.id}`, {
            title: state.editing_task.title,
            description: state.editing_task.description,
        })
        .then(res => {
            for (let i = 0; i < state.tasks.length; i++)
            {
                if (res.data.task.id === state.tasks[i].id)
                {
                    state.tasks[i].title = res.data.task.title
                    state.tasks[i].description = res.data.task.description
                }
            }

            state.modal_task_form.hide()

        })
        .catch(err => {
            debugger
        })
    }

    return axios.post(`/teams/${props.user.current_team_id}/tasks`, {
        title: state.task.title,
        description: state.task.description,
    })
    .then(res => {
        state.tasks.push(res.data.task)

        state.modal_task_form.hide()
    })
    .catch(err => {
        debugger
    })

}

function editTask(_task)
{
    state.editing_task = JSON.parse(JSON.stringify(_task))
}

function editTask_cancel(_task)
{
    state.editing_task = null
}

function toggleCompleteTask(task)
{

    axios.patch(`tasks/${task.id}`, {
        is_complete: task.is_complete
    })
        .then(res => {
            for (let i = 0; i < state.tasks.length; i++)
            {
                if (res.data.task.id === state.tasks[i].id)
                {
                    state.tasks[i].is_complete = res.data.task.is_complete
                }
            }
        })
        .catch(err => {
            debugger
        })
}

function deleteTask_init(_task)
{
    state.deleting_task = JSON.parse(JSON.stringify(_task))
    state.modal_task_delete_confirm.show()
}

function deleteTask()
{
    if (! state.deleting_task || ! state.deleting_task.id)
    {
        return
    }

    return axios.delete(`/tasks/${state.deleting_task.id}`)
        .then(res => {
            state.tasks = state.tasks.filter(t => t.id != res.data.task_id)

            state.modal_task_delete_confirm.hide()

            state.deleting_task = null
            state.editing_task = null
        })
        .catch(err => {
            debugger
        })
}

</script>

<template>
<AppLayout title="Dashboard Custom Head">
    <div class="row">
        <div class="col-xxl-8">
            <!-- start page title -->
            <div class="page-title-box">
                <div class="page-title-right">
                    <div class="app-search">
                        <form>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search..." />
                                <span class="mdi mdi-magnify search-icon"></span>
                                <button class="input-group-text btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class='uil uil-sort-amount-down'></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Due Date</a>
                                    <a class="dropdown-item" href="#">Added Date</a>
                                    <a class="dropdown-item" href="#">Assignee</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <h4 class="page-title">Tasks <a href="#" class="btn btn-success btn-sm ms-3" @click="newTask_init">Add New</a></h4>
            </div>
            <!-- end page title -->

            <!-- New Tasks Panel -->
            <div class="mt-2">
                <h5 class="m-0 pb-2">
                    <a class="text-dark" data-bs-toggle="collapse" href="#todayTasks" role="button" aria-expanded="false" aria-controls="todayTasks">
                        <i class='uil uil-angle-down font-18'></i>New <span class="text-muted">({{state.tasks.length}})</span>
                    </a>
                </h5>

                <div class="collapse show" id="todayTasks">
                    <div class="card mb-0">
                        <div class="card-body">
                            <!-- task -->
                            <div
                                v-for="task in state.tasks"
                                :key="task.id"
                                class="row justify-content-sm-between">
                                <div class="col-lg-6 mb-2 mb-lg-0 d-flex align-items-center">
                                    <div
                                        v-show="!state.editing_task || state.editing_task.id !== task.id"
                                        @click="editTask(task)"
                                        class="ln-icon-btn me-3"
                                        >
                                        <div class="mdi mdi-pencil"></div>
                                    </div>
                                    <div
                                        v-show="state.editing_task && state.editing_task.id === task.id"
                                        @click="editTask_cancel"
                                        class="ln-icon-btn me-3"
                                        >

                                        <div class="mdi mdi-cancel"></div>
                                    </div>


                                    <div class="form-check">
                                        <input
                                            @change="toggleCompleteTask(task)"
                                            v-model="task.is_complete"
                                            type="checkbox"
                                            class="form-check-input"
                                            :id="task.id"
                                            >
                                        <label
                                            class="form-check-label"
                                            :for="task.id">
                                            {{ task.title }}
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-lg-6 d-flex justify-content-between align-items-center">
                                    <div id="tooltip-container">
                                        <img src="assets/images/users/avatar-9.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                        data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to Arya S" />
                                    </div>
                                    <div>
                                        <ul class="list-inline font-13 text-end mb-0">
                                            <li class="list-inline-item">
                                                <i class='uil uil-schedule font-16 me-1'></i> Today 10am
                                            </li>
                                            <li class="list-inline-item ms-1">
                                                <i class='uil uil-align-alt font-16 me-1'></i> 3/7
                                            </li>
                                            <li class="list-inline-item ms-1">
                                                <i class='uil uil-comment-message font-16 me-1'></i> 21
                                            </li>
                                            <li class="list-inline-item ms-2">
                                                <span class="badge badge-danger-lighten p-1">High</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div> <!-- end .d-flex-->
                            </div>
                            <!-- end task -->
                        </div> <!-- end card-body-->
                    </div> <!-- end card -->
                </div> <!-- end .collapse-->
            </div> <!-- end .mt-2-->


            <!-- tasks panel -->
            <div class="mt-2">
                <h5 class="m-0 pb-2">
                    <a class="text-dark" data-bs-toggle="collapse" href="#todayTasks" role="button" aria-expanded="false" aria-controls="todayTasks">
                        <i class='uil uil-angle-down font-18'></i>Today <span class="text-muted">(10)</span>
                    </a>
                </h5>

                <div class="collapse show" id="todayTasks">
                    <div class="card mb-0">
                        <div class="card-body">
                            <!-- task -->
                            <div class="row justify-content-sm-between">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="task1">
                                        <label class="form-check-label" for="task1">
                                            Draft the new contract document for sales team
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between">
                                        <div id="tooltip-container">
                                            <img src="assets/images/users/avatar-9.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                            data-bs-container="#tooltip-container" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to Arya S" />
                                        </div>
                                        <div>
                                            <ul class="list-inline font-13 text-end">
                                                <li class="list-inline-item">
                                                    <i class='uil uil-schedule font-16 me-1'></i> Today 10am
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-align-alt font-16 me-1'></i> 3/7
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-comment-message font-16 me-1'></i> 21
                                                </li>
                                                <li class="list-inline-item ms-2">
                                                    <span class="badge badge-danger-lighten p-1">High</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- end .d-flex-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end task -->

                            <!-- task -->
                            <div class="row justify-content-sm-between mt-2">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="task2">
                                        <label class="form-check-label" for="task2">
                                            iOS App home page
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between">
                                        <div id="tooltip-container1">
                                            <img src="assets/images/users/avatar-2.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                            data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to James B" />
                                        </div>
                                        <div>
                                            <ul class="list-inline font-13 text-end">
                                                <li class="list-inline-item">
                                                    <i class='uil uil-schedule font-16 me-1'></i> Today 4pm
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-align-alt font-16 me-1'></i> 2/7
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-comment-message font-16 me-1'></i> 48
                                                </li>
                                                <li class="list-inline-item ms-2">
                                                    <span class="badge badge-danger-lighten p-1">High</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- end .d-flex-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end task -->

                            <!-- task -->
                            <div class="row justify-content-sm-between mt-2">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="task3">
                                        <label class="form-check-label" for="task3">
                                            Write a release note
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between">
                                        <div id="tooltip-container2">
                                            <img src="assets/images/users/avatar-4.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                            data-bs-container="#tooltip-container2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to Kevin C" />
                                        </div>
                                        <div>
                                            <ul class="list-inline font-13 text-end mb-0">
                                                <li class="list-inline-item">
                                                    <i class='uil uil-schedule font-16 me-1'></i> Today 6pm
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-align-alt font-16 me-1'></i> 18/21
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-comment-message font-16 me-1'></i> 73
                                                </li>
                                                <li class="list-inline-item ms-2">
                                                    <span class="badge badge-info-lighten p-1">Medium</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- end .d-flex-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end task -->
                        </div> <!-- end card-body-->
                    </div> <!-- end card -->
                </div> <!-- end .collapse-->
            </div> <!-- end .mt-2-->

            <!-- upcoming tasks -->
            <div class="mt-4">

                <h5 class="m-0 pb-2">
                    <a class="text-dark" data-bs-toggle="collapse" href="#upcomingTasks" role="button" aria-expanded="false" aria-controls="upcomingTasks">
                        <i class='uil uil-angle-down font-18'></i>Upcoming <span class="text-muted">(5)</span>
                    </a>
                </h5>

                <div class="collapse show" id="upcomingTasks">
                    <div class="card mb-0">
                        <div class="card-body">
                            <!-- task -->
                            <div class="row justify-content-sm-between">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="task4">
                                        <label class="form-check-label" for="task4">
                                            Invite user to a project
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between">
                                        <div id="tooltip-container3">
                                            <img src="assets/images/users/avatar-2.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                            data-bs-container="#tooltip-container3" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to James B" />
                                        </div>
                                        <div>
                                            <ul class="list-inline font-13 text-end">
                                                <li class="list-inline-item">
                                                    <i class='uil uil-schedule font-16 me-1'></i> Tomorrow 7am
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-align-alt font-16 me-1'></i> 1/12
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-comment-message font-16 me-1'></i> 36
                                                </li>
                                                <li class="list-inline-item ms-2">
                                                    <span class="badge badge-danger-lighten p-1">High</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- end .d-flex-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end task -->

                            <!-- task -->
                            <div class="row justify-content-sm-between mt-2">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="task5">
                                        <label class="form-check-label" for="task5">
                                            Enable analytics tracking
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between">
                                        <div id="tooltip-container4">
                                            <img src="assets/images/users/avatar-2.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                            data-bs-container="#tooltip-container4" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to James B" />
                                        </div>
                                        <div>
                                            <ul class="list-inline font-13 text-end">
                                                <li class="list-inline-item">
                                                    <i class='uil uil-schedule font-16 me-1'></i> 27 Aug 10am
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-align-alt font-16 me-1'></i> 13/72
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-comment-message font-16 me-1'></i> 211
                                                </li>
                                                <li class="list-inline-item ms-2">
                                                    <span class="badge badge-success-lighten p-1">Low</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- end .d-flex-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end task -->

                            <!-- task -->
                            <div class="row justify-content-sm-between mt-2">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="task6">
                                        <label class="form-check-label" for="task6">
                                            Code HTML email template
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between">
                                        <div id="tooltip-container5">
                                            <img src="assets/images/users/avatar-7.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                            data-bs-container="#tooltip-container5" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to Edward S" />
                                        </div>
                                        <div>
                                            <ul class="list-inline font-13 text-end mb-0">
                                                <li class="list-inline-item">
                                                    <i class='uil uil-schedule font-16 me-1'></i> No Due Date
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-align-alt font-16 me-1'></i> 0/7
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-comment-message font-16 me-1'></i> 0
                                                </li>
                                                <li class="list-inline-item ms-2">
                                                    <span class="badge badge-info-lighten p-1">Medium</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- end .d-flex-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end task -->
                        </div> <!-- end card-body-->
                    </div> <!-- end card -->
                </div> <!-- end collapse-->
            </div>
            <!-- end upcoming tasks -->

            <!-- start other tasks -->
            <div class="mt-4 mb-4">

                <h5 class="m-0 pb-2">
                    <a class="text-dark" data-bs-toggle="collapse" href="#otherTasks" role="button" aria-expanded="false" aria-controls="otherTasks">
                        <i class='uil uil-angle-down font-18'></i>Other <span class="text-muted">(3)</span>
                    </a>
                </h5>

                <div class="collapse show" id="otherTasks">
                    <div class="card mb-0">
                        <div class="card-body">
                            <!-- task -->
                            <div class="row justify-content-sm-between">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="task7">
                                        <label class="form-check-label" for="task7">
                                            Coordinate with business development
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between">
                                        <div id="tooltip-container6">
                                            <img src="assets/images/users/avatar-9.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                            data-bs-container="#tooltip-container6" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to Arya S" />
                                        </div>
                                        <div>
                                            <ul class="list-inline font-13 text-end">
                                                <li class="list-inline-item">
                                                    <i class='uil uil-schedule font-16 me-1'></i> Today 10am
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-align-alt font-16 me-1'></i> 5/14
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-comment-message font-16 me-1'></i> 71
                                                </li>
                                                <li class="list-inline-item ms-2">
                                                    <span class="badge badge-info-lighten p-1">Medium</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- end .d-flex-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end task -->

                            <!-- task -->
                            <div class="row justify-content-sm-between mt-2">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="task8">
                                        <label class="form-check-label" for="task8">
                                            Kanban board design
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between">
                                        <div id="tooltip-container7">
                                            <img src="assets/images/users/avatar-2.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                            data-bs-container="#tooltip-container7" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to James B" />
                                        </div>
                                        <div>
                                            <ul class="list-inline font-13 text-end">
                                                <li class="list-inline-item">
                                                    <i class='uil uil-schedule font-16 me-1'></i> No Due Date
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-align-alt font-16 me-1'></i> 0/8
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-comment-message font-16 me-1'></i> 0
                                                </li>
                                                <li class="list-inline-item ms-2">
                                                    <span class="badge badge-info-lighten p-1">Medium</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- end .d-flex-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end task -->

                            <!-- task -->
                            <div class="row justify-content-sm-between mt-2">
                                <div class="col-sm-6 mb-2 mb-sm-0">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="task9">
                                        <label class="form-check-label" for="task9">
                                            Code HTML email template for sales outreach
                                        </label>
                                    </div> <!-- end checkbox -->
                                </div> <!-- end col -->
                                <div class="col-sm-6">
                                    <div class="d-flex justify-content-between">
                                        <div id="tooltip-container8">
                                            <img src="assets/images/users/avatar-5.jpg" alt="image" class="avatar-xs rounded-circle me-1"
                                            data-bs-container="#tooltip-container8" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Assigned to Gary H" />
                                        </div>
                                        <div>
                                            <ul class="list-inline font-13 text-end mb-0">
                                                <li class="list-inline-item">
                                                    <i class='uil uil-schedule font-16 me-1'></i> 10 Sep 3pm
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-align-alt font-16 me-1'></i> 0/58
                                                </li>
                                                <li class="list-inline-item ms-1">
                                                    <i class='uil uil-comment-message font-16 me-1'></i> 9
                                                </li>
                                                <li class="list-inline-item ms-2">
                                                    <span class="badge badge-success-lighten p-1">Low</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div> <!-- end .d-flex-->
                                </div> <!-- end col -->
                            </div>
                            <!-- end task -->
                        </div> <!-- end card-body-->
                    </div> <!-- end card -->
                </div>
            </div>
        </div> <!-- end col -->

        <!-- task details -->
        <!-- Populate when Task is clicked -->
        <div
            v-show="state.editing_task"
            class="col-xxl-4">
            <div class="card">
                <div class="card-body">
                    <div class="dropdown card-widgets">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class='uil uil-ellipsis-h'></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class='uil uil-file-upload me-1'></i>Attachment
                            </a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class='uil uil-edit me-1'></i>Edit
                            </a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                <i class='uil uil-file-copy-alt me-1'></i>Mark as Duplicate
                            </a>
                            <div class="dropdown-divider"></div>
                            <!-- item-->
                            <a @click="deleteTask_init(state.editing_task)" class="dropdown-item text-danger">
                                <i class='uil uil-trash-alt me-1'></i>Delete
                            </a>
                        </div> <!-- end dropdown menu-->
                    </div> <!-- end dropdown-->

                    <div class="form-check float-start">
                        <input type="checkbox" class="form-check-input" id="completedCheck">
                        <label class="form-check-label" for="completedCheck">
                            Mark as completed
                        </label>
                    </div> <!-- end form-check-->

                    <hr class="mt-4 mb-2" />

                    <div class="row">
                        <div class="col">

                            <h4>
                                <input v-if="state.editing_task"
                                    v-model="state.editing_task.title"
                                    type="text"
                                    class="form-input"
                                    style="border: 0; border-bottom: 1px solid #d5d5d5;"
                                     />
                            </h4>

                            <div class="row">
                                <div class="col-6">
                                    <!-- assignee -->
                                    <p class="mt-2 mb-1 text-muted">Assigned To</p>
                                    <div class="d-flex">
                                        <img src="assets/images/users/avatar-9.jpg" alt="Arya S" class="rounded-circle me-2" height="24" />
                                        <div>
                                            <h5 class="mt-1 font-14">
                                                Arya Stark
                                            </h5>
                                        </div>
                                    </div>
                                    <!-- end assignee -->
                                </div> <!-- end col -->

                                <div class="col-6">
                                    <!-- start due date -->
                                    <p class="mt-2 mb-1 text-muted">
                                        <i class='uil uil-schedule font-18 text-success me-1'></i>
                                        Due Date
                                    </p>
                                    <div class="d-flex">
                                        <div>
                                            <h5 class="mt-1 font-14">
                                                <!-- Single Date Picker -->
                                                <div class="mb-3">
                                                    <input type="text"
                                                        class="form-control date" id="date-picker"
                                                        data-toggle="date-picker" data-single-date-picker="true">
                                                </div>
                                            </h5>
                                        </div>
                                    </div>
                                    <!-- end due date -->
                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <!-- task description -->
                            <div class="row mt-3" v-if="state.editing_task">
                                <div class="col">
                                    <textarea
                                        v-model="state.editing_task.description"
                                        >
                                    </textarea> <!-- end Snow-editor-->

                                </div> <!-- end col -->
                            </div>
                            <!-- end task description -->

                            <div class="d-flex align-items-center justify-content-end mb-3">
                                <button
                                    @click="saveTask"
                                    class="btn btn-success">Save</button>
                            </div>

                            <!-- start sub tasks/checklists -->
                            <h5 class="mt-4 mb-2 font-16">Checklists/Sub-tasks</h5>
                            <div class="form-check mt-1">
                                <input type="checkbox" class="form-check-input" id="checklist1">
                                <label class="form-check-label strikethrough" for="checklist1">
                                    Find out the old contract documents
                                </label>
                            </div>

                            <div class="form-check mt-1">
                                <input type="checkbox" class="form-check-input" id="checklist2">
                                <label class="form-check-label strikethrough" for="checklist2">
                                    Organize meeting sales associates to understand need in detail
                                </label>
                            </div>

                            <div class="form-check mt-1">
                                <input type="checkbox" class="form-check-input" id="checklist3">
                                <label class="form-check-label strikethrough" for="checklist3">
                                    Make sure to cover every small details
                                </label>
                            </div>
                            <!-- end sub tasks/checklists -->

                            <!-- start attachments -->
                            <h5 class="mt-4 mb-2 font-16">Attachments</h5>
                            <div class="card mb-2 shadow-none border">
                                <div class="p-1">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="avatar-sm">
                                                <span class="avatar-title rounded">
                                                    .ZIP
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col ps-0">
                                            <a href="javascript:void(0);" class="text-muted fw-bold">sales-assets.zip</a>
                                            <p class="mb-0">2.3 MB</p>
                                        </div>
                                        <div class="col-auto" id="tooltip-container9">
                                            <!-- Button -->
                                            <a href="javascript:void(0);" data-bs-container="#tooltip-container9" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Download"
                                                class="btn btn-link text-muted btn-lg p-0">
                                                <i class='uil uil-cloud-download'></i>
                                            </a>
                                            <a href="javascript:void(0);" data-bs-container="#tooltip-container9" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"
                                                class="btn btn-link text-danger btn-lg p-0">
                                                <i class='uil uil-multiply'></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-2 shadow-none border">
                                <div class="p-1">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <img src="assets/images/projects/project-1.jpg"
                                                class="avatar-sm rounded" alt="file-image">
                                        </div>
                                        <div class="col ps-0">
                                            <a href="javascript:void(0);" class="text-muted fw-bold">new-contarcts.docx</a>
                                            <p class="mb-0">1.25 MB</p>
                                        </div>
                                        <div class="col-auto" id="tooltip-container10">
                                            <!-- Button -->
                                            <a href="javascript:void(0);" data-bs-container="#tooltip-container10" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Download"
                                                class="btn btn-link text-muted btn-lg p-0">
                                                <i class='uil uil-cloud-download'></i>
                                            </a>
                                            <a href="javascript:void(0);" data-bs-container="#tooltip-container10" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"
                                                class="btn btn-link text-danger btn-lg p-0">
                                                <i class='uil uil-multiply'></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end attachments -->

                            <!-- comments -->
                            <div class="row mt-3">
                                <div class="col">
                                    <h5 class="mb-2 font-16">Comments</h5>

                                    <div class="d-flex mt-3 p-1">
                                        <img src="assets/images/users/avatar-9.jpg" class="me-2 rounded-circle"
                                            height="36" alt="Arya Stark" />
                                        <div class="w-100">
                                            <h5 class="mt-0 mb-0">
                                                <span class="float-end text-muted font-12">4:30am</span>
                                                Arya Stark
                                            </h5>
                                            <p class="mt-1 mb-0 text-muted">
                                                Should I review the last 3 years legal documents as well?
                                            </p>
                                        </div>
                                    </div> <!-- end comment -->

                                    <hr />

                                    <div class="d-flex mt-2 p-1">
                                        <img src="assets/images/users/avatar-5.jpg"
                                            class="me-2 rounded-circle" height="36" alt="Dominc B" />
                                        <div class="w-100">
                                            <h5 class="mt-0 mb-0">
                                                <span class="float-end text-muted font-12">3:30am</span>
                                                Gary Somya
                                            </h5>
                                            <p class="mt-1 mb-0 text-muted">
                                                @Arya FYI..I have created some general guidelines last year.
                                            </p>
                                        </div>
                                    </div> <!-- end comment-->

                                    <hr />

                                </div> <!-- end col -->
                            </div> <!-- end row -->

                            <div class="row mt-2">
                                <div class="col">
                                    <div class="border rounded">
                                        <form action="#" class="comment-area-box">
                                            <textarea rows="3" class="form-control border-0 resize-none"
                                            placeholder="Your comment..."></textarea>
                                            <div class="p-2 bg-light">
                                                <div class="float-end">
                                                    <button type="submit" class="btn btn-sm btn-success"><i class='uil uil-message me-1'></i>Submit</button>
                                                </div>
                                                <div>
                                                    <a href="#" class="btn btn-sm px-1 btn-light"><i class='uil uil-cloud-upload'></i></a>
                                                    <a href="#" class="btn btn-sm px-1 btn-light"><i class='uil uil-at'></i></a>
                                                </div>
                                            </div>
                                        </form>
                                    </div> <!-- end .border-->
                                </div> <!-- end col-->
                            </div> <!-- end row-->
                            <!-- end comments -->
                        </div> <!-- end col -->
                    </div> <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>
    <!-- end row-->



    <!-- Modals -->

    <div
        id="modal_task_form"
        class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_task_form_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="modal_task_form_label">Modal Heading</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">



                    <div class="mb-3">
                        <label for="task_title" class="form-label">Title</label>
                        <input
                            id="task_title"
                            class="form-control"
                            v-model="state.task.title"

                            type="text"
                            >
                    </div>

                    <div class="mb-3">
                        <label for="task_description" class="form-label">Description</label>
                        <input
                            v-model="state.task.description"
                            type="text" id="task_description" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="saveTask">Save</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div
        id="modal_task_delete_confirm"
        class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal_task_delete_confirm_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-primary">
                    <h4 class="modal-title" id="modal_task_delete_confirm_label">Delete Task</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this task?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="deleteTask">Confirm</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</AppLayout>
</template>
