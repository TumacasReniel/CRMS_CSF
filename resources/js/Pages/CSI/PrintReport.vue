<script setup>
    const props = defineProps({
        data: {
            type: Object,
        },
    });
</script>
<template>
        <div class="print-id print">
            <div style="width: 100%;">
               <h2 style="text-align:center">Customer Satisfaction Feedback</h2>
               {{ data }}
            </div>  
        </div>
</template>

<style scoped>
    @import url("https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;700&display=swap");
    * {
    font-family: "Noto Sans", sans-serif;
    }
    /* Clear floats fter the columns */
    .print {
    display: none;
    }
    @media print {
    .print-id {
        display: block;
    }
    }
</style>