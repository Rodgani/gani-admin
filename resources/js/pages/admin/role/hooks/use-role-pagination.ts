import { PER_PAGE_DEFAULT } from '@/constants/app';
import { fetchRoles } from '../services/role-service';


export function useRolePagination(currentPage: number, search: string) {
  const handleSearch = () => {
    const params = {
      page: currentPage,
      per_page: PER_PAGE_DEFAULT,
      ...(search ? { search } : {}),
    };

    fetchRoles(params);
  };

  const handlePageChange = (page: number) => {
    if (page < 1) return;

    const params = {
      page,
      per_page: PER_PAGE_DEFAULT,
      ...(search ? { search } : {}),
    };

    fetchRoles(params);
  };

  return { handleSearch, handlePageChange };
}
