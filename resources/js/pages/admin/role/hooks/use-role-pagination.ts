import { fetchRoles } from '../services/role-service';
import { usePagination } from '@/hooks/use-pagination';


export function useRolePagination(currentPage: number, filterParams: Record<string, any>) {
  const { handleSearch, handlePageChange } = usePagination(filterParams, fetchRoles);

  return {
    handleSearch: () => handleSearch(currentPage),
    handlePageChange,
  };
}
