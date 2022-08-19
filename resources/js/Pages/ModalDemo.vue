<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { reactive, onMounted } from 'vue'
import Modal from '@/Shared/Modal.vue'

const state = reactive({
	modal_demo: null,
    clients: [],

    client_editing: null,
    client: {
        name: null
    },
})


onMounted(() => {
	state.modal_demo = new bootstrap.Modal('#modal_demo', {})

    state.modal_demo._element.addEventListener('hide.bs.modal', () => {
        state.client_editing = null
        state.client = {name: null}
    })
})


function createClient_init()
{
    state.client_editing = null
    state.client = {name: null}

	state.modal_demo.show()
}

function closeModal()
{
	state.modal_demo.hide()
}

function editClient_init(client)
{
    state.client = JSON.parse(JSON.stringify(client))

    state.client_editing = client

    state.modal_demo.show()
}

function saveClient()
{
    // if editing
    if (state.client_editing && state.client_editing.name)
    {

        state.clients = state.clients.map(c => {
            if (c.name == state.client_editing.name)
            {
                c = state.client
            }

            return c
        })

        // reset the editing state
        state.client_editing = null
        // reset the form
        state.client = {name: null}

        state.modal_demo.hide()

        return
    }


    // creating a new client
    state.clients.push(JSON.parse(JSON.stringify(state.client)))

    // reset our form
    state.client = {name: null}
    state.modal_demo.hide()

}

</script>


<template>
<AppLayout title="Modal Demo">


		<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary" @click="createClient_init" >
		Launch demo modal
	</button>


    <div class="p-5 mx-auto">
        <ul class="list-group">
            <li
                v-for="client in state.clients"
                :key="client.name"
                class="list-group-item"
                @click="editClient_init(client)"
                >
                {{ client.name }}

            </li>
        </ul>
    </div>


    <Modal
        id="modal_demo"
        :custom_classes="['modal-lg']"
        :close_func="closeModal"
    >

        <template #title>
            <span v-if="state.client_editing && state.client_editing.name">Editing {{ state.client_editing.name }}</span>
            <span v-else>Create a New Client</span>
        </template>

        <template #body>
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input
                v-model="state.client.name"
                type="text" class="form-control"
                id="name">
            </div>

        </template>

        <template #footer>
            <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
            <button type="button" class="btn btn-primary" @click="saveClient">
                <span v-if="state.client_editing && state.client_editing.name">Save Changes</span>
                <span v-else>Create</span>
            </button>

        </template>

    </Modal>
</AppLayout>
</template>
