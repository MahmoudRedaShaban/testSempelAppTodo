<template>
  <div class="p-20 bg-blue-100">
    <h3 class="text-blue-300 mb-4 text-sm font-bold">All Status Mang</h3>
    <div class="bg-white mb-4 p-6 rounded-lg shadow-lg w-full">
      <AddNewStatus v-on:state-added="hundeAfterAdd" />
    </div>
    <div
      v-for="statu in statusAll"
      :key="statu.id"
      class="bg-white mb-4 p-6 rounded-lg shadow-lg"
    >
      <h2 class="text-2xl font-bold mb-2 text-gray-800">{{ statu.title }}</h2>
      <span @click="deleteStatue(statu)" class="float-right htop w-8 mb-4"
        ><svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
          /></svg
      ></span>
    </div>
  </div>
</template>
<script>
import AddNewStatus from "./AddNewStatus.vue";
export default {
  components: { AddNewStatus },
  props: {
    dataAll: Array,
  },
  data() {
    return {
      statusAll: Array,
    };
  },
  mounted() {
    this.statusAll = this.dataAll;
    console.log(this.dataAll);
  },
  methods: {
    hundeAfterAdd(state) {
      console.log(state);
      this.statusAll.push(state);
    },
    deleteStatue(statu) {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "You won't Remove Statue",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            axios
              .delete(`/statuses/${statu.id}`)
              .then((res) => {
                this.$swal.fire(
                  "Deleted!",
                  "Your Status  has been deleted.",
                  "success"
                );
                // Tell the parent component we've added a new task and include it
                this.statusAll = res.data;
                // console.log(res.data);
              })
              .catch((err) => {
                this.$swal.fire(
                  "Faild Deleted!",
                  "Your Statue has been  Not deleted.",
                  "warning"
                );
                console.log(err);
              });
          }
        });
    },
  },
};
</script>
<style scoped>
svg {
  color: red;
  display: block;
}
</style>
