<template>
    <div class="chat_app">
        <Conversation :contact="selectedContact" :messages="messages" @new="saveNewMessage"></Conversation>
        <ContactsList :contacts="contacts" @selected="startConversationWith"></ContactsList>
    </div>
</template>

<script>
    import Conversation from "./Conversation";
    import ContactsList from "./ContactsList";
    export default {
        props:{
          user:{
              required:true,
              type:Object,
          }
        },
        data(){
            return{
                selectedContact:null,
                messages:[],
                contacts:[],
            }
        },
        mounted() {
            axios.get('/contacts')
            .then((response)=>{
                console.log(response.data)
                this.contacts=response.data;
            })
        },
        components:{ContactsList,Conversation},
        methods:{
            startConversationWith(contact){
                axios.get(`/conversation/${contact.id}`)
                .then((response)=>{
                    this.messages=response.data;
                    this.selectedContact=contact;
                })
            },
            saveNewMessage(text){
                this.messages.push(text)
            }
        }
    }
</script>
<style lang="scss" scoped>
    .chat_app{
        display: flex;
    }
</style>
