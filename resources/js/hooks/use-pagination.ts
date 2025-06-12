import { PER_PAGE_DEFAULT } from '@/constants/app';

export function usePagination<TParams extends Record<string, any>>(
  filterParams: TParams,
  fetchFn: (params: TParams & { page: number; per_page: number }) => void
) {
  const handleSearch = (page: number) => {
    const params = {
      page,
      per_page: PER_PAGE_DEFAULT,
      ...filterParams,
    };

    fetchFn(params);
  };

  const handlePageChange = (page: number) => {
    if (page < 1) return;

    const params = {
      page,
      per_page: PER_PAGE_DEFAULT,
      ...filterParams,
    };

    fetchFn(params);
  };

  return { handleSearch, handlePageChange };
}
