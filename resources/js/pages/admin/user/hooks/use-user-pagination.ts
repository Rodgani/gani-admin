// hooks/useUserPagination.ts
import { PER_PAGE_DEFAULT } from '@/constants/app';
import { fetchUsers } from '../services/user-service';


export function useUserPagination(currentPage: number, search: string) {
  const handleSearch = () => {
    const params = {
      page: currentPage,
      per_page: PER_PAGE_DEFAULT,
      ...(search ? { search } : {}),
    };

    fetchUsers(params);
  };

  const handlePageChange = (page: number) => {
    if (page < 1) return;

    const params = {
      page,
      per_page: PER_PAGE_DEFAULT,
      ...(search ? { search } : {}),
    };

    fetchUsers(params);
  };

  return { handleSearch, handlePageChange };
}
