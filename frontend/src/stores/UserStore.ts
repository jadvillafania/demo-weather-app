import { defineStore } from "pinia";

interface State {
  pagination: Pagination;
}

export const useUserStore = defineStore("UserStore", {
  state: (): State => ({
    pagination: {
      per_page: 10,
      status: {
        error: false,
        success: false,
        pending: true,
        message: "",
      },
    } as Pagination,
  }),
  actions: {
    changePageSize(size: number = 5) {
      this.pagination.per_page = size;
    },
    async fetchData(
      page: number | null = 1,
      signal: AbortSignal | null = null
    ) {
      this.pagination.data = [];
      this.pagination.status = {
        error: false,
        success: false,
        pending: true,
        message: "",
      };

      try {
        let url = `http://localhost/users?page=${page}`;
        if (this.pagination.per_page)
          url += `&per_page=${this.pagination.per_page}`;

        let request = null;
        try {
          request = await fetch(url, { signal: signal });
        } catch (err: any) {
          if (err.name != "AbortError") {
            console.error(err.message);
          }
          return;
        }
        const response = await request?.json();

        this.pagination = response;
        this.pagination.data.forEach((user) => {
          user.status = {
            pending: true,
            success: false,
            message: "Fetching data.",
          } as Status;
        });

        this.pagination.status = {
          pending: false,
          success: true,
          message: "Success fetching users.",
        } as Status;
      } catch (err) {
        this.pagination.status.error = true;
        this.pagination.status.pending = false;
        this.pagination.status.message = "Error fetching users.";
        throw err;
      }
    },
    async fetchUserWeather(user: User) {
      if (user.weather) return;

      const url = `http://localhost/user/${user.id}/weather`;

      try {
        const data = await (await fetch(url)).json();
        user.weather = data;
        user.status.error = false;
        user.status.success = true;
        user.status.pending = false;
        user.status.message = "Success fetching user weather info.";
      } catch (err) {
        user.status.error = true;
        user.status.pending = false;
        user.status.message = "Error fetching user weather info.";
        throw err;
      }
    },
  },
});
