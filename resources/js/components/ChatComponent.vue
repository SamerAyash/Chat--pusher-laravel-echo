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
            Echo.private('messages.'+this.user.id).listen(
                "NewMessage",
                (e) => {
                    this.handelIncoming(e.message);
                });

            axios.get('/contacts')
            .then((response)=>{
                this.contacts=response.data;
            })
        },
        components:{ContactsList,Conversation},
        methods:{
            startConversationWith(contact){
                this.updateUnreadCount(contact,true)
                axios.get(`/conversation/${contact.id}`)
                .then((response)=>{
                    this.messages=response.data;
                    this.selectedContact=contact;
                })
            },
            saveNewMessage(message){
                this.messages.push(message)
            },
            handelIncoming(message){
                if (this.selectedContact && message.from == this.selectedContact.id){
                    this.saveNewMessage(message);
                }
                this.updateUnreadCount(message.from_contact,false);
                },
            updateUnreadCount(contact,reset){
                this.contacts = this.contacts.map((single)=>{
                   if(single.id != contact.id){
                       return single;
                   }
                   if (reset){
                       single.unread=0
                   }
                   else{
                       single.unread +=1;
                   }
                   return single;
                });
            }
        }
    }
</script>
<style lang="scss" scoped>
    .chat_app{
        display: flex;
    }
</style>
