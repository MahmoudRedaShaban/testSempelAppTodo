<template>
  <div class="relative p-2 flex overflow-x-auto h-full">

    <!-- Columns (Statuses) -->
    <div
      v-for="status in statuses"
      :key="status.slug"
      class="mr-6 w-4/5 max-w-xs flex-1 flex-shrink-0"
    >
      <div class="rounded-md shadow-md overflow-hidden">
        <div class="p-3 flex justify-between items-baseline bg-blue-800 ">
          <h4 class="font-medium text-white">
            {{ status.title }}
          </h4>
          <button
            @click="openAddTaskForm(status.id)"
            class="py-1 px-2 text-sm text-orange-500 hover:underline">
            Add Task
          </button>
        </div>
        <div class="p-2 flex-1 flex flex-col h-full overflow-x-hidden overflow-y-auto bg-blue-100">
             <!-- AddTaskForm -->
        <AddTaskForm
                v-if="newTaskForStatus === status.id"
                :status-id="status.id"
                v-on:task-added="handleTaskAdded"
                v-on:task-canceled="closeAddTaskForm"
                                        />
                                                  <!-- ./AddTaskForm -->
            <UpdatedTaskForm
                v-if="updateTaskdata.status_id == status.id"
                :status-id="status.id"
                :taskupdate="updateTaskdata"
                v-on:task-added="handleTaskUpdateded"
                v-on:task-canceled="closeAddTaskForm"
                                        />
          <!-- Tasks -->
          <draggable
                class="flex-1 overflow-hidden"
                v-model="status.tasks"
                v-bind="taskDragOptions"
                @end="handleTaskMoved"
                >
                <transition-group
                    class="flex-1 flex flex-col h-full overflow-x-hidden overflow-y-auto rounded shadow-xs"
                    tag="div"
                >
          <div
            v-for="task in status.tasks"
            :key="task.id"
            class="mb-3 p-3 h-24 flex flex-col bg-white rounded-md shadow transform hover:shadow-md cursor-pointer">
            
            <span class="block mb-2 text-xl text-gray-900">
              {{ task.title }}
            </span>
            <p class="text-gray-700  block mb-2">
              {{ task.description }}
            </p>
            <div class=" text-right block mb-2">  
                <button class="flex-start hover:bg-red-600" @click="deleteTask(task)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
</svg>
            </button>
            <button class="flex-end hover:bg-teal-600" @click="updateTaskh(task)"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
  <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
  <path stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11h4m-2-2v4" />
</svg></button>
            </div>
            
          </div>
          <!-- ./Tasks -->
        </transition-group>
        </draggable>
          <!-- No Tasks -->
          <div
            v-show="!status.tasks.length && newTaskForStatus !== status.id"
            class="flex-1 p-4 flex flex-col items-center justify-center"
          >
            <span class="text-gray-600">No tasks yet</span>
            <button
              class="mt-1 text-sm text-orange-600 hover:underline"
              @click="openAddTaskForm(status.id)"
            >
              Add one
            </button>
          </div>
          <!-- ./No Tasks -->
        </div>
      </div>
    </div>
    <!-- ./Columns -->

  </div>
</template>

<script>
import draggable from "vuedraggable"; // import the vuedraggable component
import AddTaskForm from "./AddTaskForm"; // import the component
import UpdatedTaskForm from "./UpdateTaskForm"; // import the component
export default {
  components: { draggable ,UpdatedTaskForm,AddTaskForm }, // register component
  props: {
    initialData: Array
  },
  data() {
    return {
      statuses: [] ,
       newTaskForStatus: 0, // track the ID of the status we want to add to
       updateTaskdata: {}
    };
  },
   computed: {
    taskDragOptions() {
      return {
        animation: 200,
        group: "task-list",
        dragClass: "status-drag"
      };
    }
  },
  mounted() {
    // 'clone' the statuses so we don't alter the prop when making changes
    this.statuses = JSON.parse(JSON.stringify(this.initialData));
  },
  methods: {
      updateTaskh(task){
          this.updateTaskdata = task;
      },
    // set the statusId and trigger the form to show 
    openAddTaskForm(statusId) {
      this.newTaskForStatus = statusId;
    },
    // reset the statusId and close form
    closeAddTaskForm() {
        this.updateTaskdata={status_id:0};
      this.newTaskForStatus = 0;
    },
    // add a task to the correct column in our list
    handleTaskAdded(newTask) {
      // Find the index of the status where we should add the task
      const statusIndex = this.statuses.findIndex(
        status => status.id === newTask.status_id
      );

      // Add newly created task to our column
      this.statuses[statusIndex].tasks.push(newTask);

      // Reset and close the AddTaskForm
      this.closeAddTaskForm();
    },
    handleTaskUpdateded(alldata){
        console.log(this.updateTaskdata.status_id);
    // Find the index of the status where we should add the task
      this.statuses = JSON.parse(JSON.stringify(alldata));

      this.updateTaskdata={status_id:0};
    // Reset and close the AddTaskForm
      this.closeAddTaskForm();
    },
    deleteTask(task){
         axios.delete(`/tasks/${task.id}`).then(res => {
      this.statuses = JSON.parse(JSON.stringify(res.data));
         }).catch(err => {
        console.log(err.response);
      });
    },
    handleTaskMoved() {
      // Send the entire list of statuses to the server
      axios.put("/tasks/sync", {columns: this.statuses}).catch(err => {
        console.log(err.response);
      });
    }
  }
};
</script>

<style scoped>
.status-drag {
  transition: transform 0.5s;
  transition-property: all;
}
</style>