<template>
  <div class="bg-green-200 py-10">
    <!--   tip; mx-auto -- jagab ilusti keskele  -->
    <div class="bg-white p-10 md:w-3/4 lg:w-1/2 mx-auto">
      <form @submit.prevent="addNewStatus">
        <!--       flex - asjad korvuti, nb! flex-1 - element kogu ylejaanud laius -->
        <div class="flex items-center mb-5">
          <!--         tip - here neede inline-block , but why? -->
          <label
            for="name"
            class="inline-block w-20 mr-6 text-right font-bold text-gray-600"
            >Title</label
          >
          <input
            type="text"
            required
            id="name"
            v-model="statusnew.title"
            placeholder="title"
            class="
              flex-1
              py-2
              border-b-2 border-gray-400
              focus:border-green-400
              text-gray-600
              placeholder-gray-400
              outline-none
            "
          />
        </div>

        <div class="flex items-center mb-10">
          <label
            for="twitter"
            class="inline-block w-20 mr-6 text-right font-bold text-gray-600"
            >Order</label
          >
          <input
            type="number"
            min="0"
            max="10"
            id="twitter"
            v-model="statusnew.order"
            class="
              flex-1
              py-2
              border-b-2 border-gray-400
              text-gray-600
              placeholder-gray-400
            "
          />
          <!-- check other class spec upper section -->
        </div>
        <div v-show="errorMessage">
          <span class="text-xs text-red-500">
            {{ errorMessage }}
          </span>
        </div>
        <div class="text-right">
          <button class="py-3 px-8 bg-green-400 text-white font-bold">
            Add New
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      statusnew: {
        title: "",
        order: 0,
      },
      errorMessage: "",
    };
  },
  methods: {
    addNewStatus() {
      // Basic validation so we don't send an empty Statu to the server
      if (!this.statusnew.title) {
        this.errorMessage = "The title field is required";
        return;
      }
      // Send new task to server
      axios
        .post("/statuses", this.statusnew)
        .then((res) => {
          // Tell the parent component we've added a new task and include it
          this.$emit("state-added", res.data);
          this.$swal.fire({
            position: "top-end",
            icon: "success",
            title: "Your Status has been Added",
            showConfirmButton: false,
            timer: 1000,
          });
          this.resetForm();
        })
        .catch((err) => {
          // Handle the error returned from our request
          this.$swal.fire({
            position: "bottom-end",
            icon: "warning",
            title: "Your Status has been Not Added",
            showConfirmButton: false,
            timer: 1000,
          });
          this.handleErrors(err);
        });
    },
    resetForm() {
      this.statusnew.title = "";
      this.statusnew.order = 0;
    },
    handleErrors(err) {
      if (err.response && err.response.status === 422) {
        // We have a validation error
        const errorBag = err.response.data.errors;
        if (errorBag.title) {
          this.errorMessage = errorBag.title[0];
        } else if (errorBag.description) {
          this.errorMessage = errorBag.description[0];
        } else {
          this.errorMessage = err.response.message;
        }
      } else {
        // We have bigger problems
        console.log(err.response);
      }
    },
  },
};
</script>
<style>
</style>
