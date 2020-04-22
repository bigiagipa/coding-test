<template>
    
    <div>

        <div class="alert alert-primary" role="alert" v-for="(value, key) in errorsValidation"  :key="key">
            <strong>{{ value }}</strong>
        </div>
            
    </div>
    
</template>

<script>
    export default {
        data(){
            return {
                
            }
        },
        props: ['errors'],
        
        computed: {
            errorsValidation(){
                let errors = Object.values(this.errors);
                errors = errors.flat();
                if(errors.length) {
                    $('#errorsValidationModal').modal('show');
                }
                return errors;
            }
        }
    }
</script>