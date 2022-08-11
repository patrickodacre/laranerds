<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { reactive, onMounted } from 'vue'

const state = reactive({
	modal_demo: null
})

onMounted(() => {
	state.modal_demo = new bootstrap.Modal('#modal_demo', {})
})


function openModal()
{
	state.modal_demo.show()
}

function closeModal()
{
	state.modal_demo.hide()
}

</script>




<template>
<AppLayout title="Modal Demo">


		<!-- Button trigger modal -->
	<button type="button" class="btn btn-primary" @click="openModal" >
		Launch demo modal
	</button>

<!-- Modal -->
<div class="modal fade" id="modal_demo" tabindex="-1" aria-labelledby="modal_demo_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_demo_label">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

</AppLayout>
</template>
