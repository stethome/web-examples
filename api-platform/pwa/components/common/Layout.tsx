import { ReactNode, useState } from "react";
import {
  DehydratedState,
  Hydrate,
  QueryClient,
  QueryClientProvider,
} from "react-query";
import {useRouter} from "next/router";
import NavBar from "../app/NavBar";

const Layout = ({
  children,
  dehydratedState,
}: {
  children: ReactNode;
  dehydratedState: DehydratedState;
}) => {
  const [queryClient] = useState(() => new QueryClient());
  const router = useRouter();

  if (router.pathname.startsWith('/app')) {
    return (<>
      <div className="w-full flex flex-col sm:flex-row flex-grow overflow-hidden">
        <div className="sm:w-1/3 md:1/4 w-full flex-shrink flex-grow-0 p-4">
          <div className="sticky top-0 p-4 w-full bg-gray-100 rounded">
            <NavBar />
          </div>
        </div>
        <main role="main" className="w-full h-full flex-grow p-3 overflow-auto">
          <QueryClientProvider client={queryClient}>
            <Hydrate state={dehydratedState}>{children}</Hydrate>
          </QueryClientProvider>
        </main>
      </div>
      <footer className="bg-blue-500 mt-auto">
        <div className="px-4 py-1 text-white mx-auto">
          <h1 className="text-2xl hidden sm:block mb-2">Footer</h1>
          <div className="flex">
            <div className="flex flex-col">
              <a href="https://stethome.com" className="text-xs uppercase tracking-wider py-2">Website</a>
              <a href="https://github.com/stethome" className="text-xs uppercase tracking-wider py-2">GitHub</a>
            </div>
          </div>
          <div className="text-right text-xs py-2">
            <span>&copy;2023 Stethome</span>
          </div>
        </div>
      </footer>
    </>)
  }

  return (
    <QueryClientProvider client={queryClient}>
      <Hydrate state={dehydratedState}>{children}</Hydrate>
    </QueryClientProvider>
  );
};

export default Layout;
