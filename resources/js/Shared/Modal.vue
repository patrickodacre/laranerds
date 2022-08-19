<script setup>
import { computed, onMounted } from 'vue';

const props = defineProps({
	id: String,
	close_func: Function,
	custom_classes: Array,
})

const base_classes = ['modal-dialog', 'fade']

const final_classes = computed(() => {
	return [...props.custom_classes, ...base_classes]
})

</script>

<template>


<!-- Modal -->
<div
	:id="props.id"
	:class="final_classes"
	tabindex="-1"
	:aria-labelledby="props.id + '_label'"
	aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"
	        :id="props.id + '_label'">
        	<slot name="title"></slot>
        </h5>
        <button
        	type="button"
        	class="btn-close"
        	aria-label="Close"
        	@click="props.close_func"></button>
      </div>
      <div class="modal-body">
			<slot name="body"></slot>
      </div>
      <div class="modal-footer">
      		<slot name="footer">DEFAULT </slot>
     </div>
    </div>
  </div>
</div>




</template>


