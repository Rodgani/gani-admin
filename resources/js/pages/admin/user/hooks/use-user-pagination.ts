import { fetchUsers } from '../services/user-service';
import { usePagination } from '@/hooks/use-pagination';

export function useUserPagination(currentPage: number, filterParams: Record<string, any>) {
  const { handleSearch, handlePageChange } = usePagination(filterParams, fetchUsers);

  return {
    handleSearch: () => handleSearch(currentPage),
    handlePageChange,
  };
}
