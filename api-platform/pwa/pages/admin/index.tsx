import Head from "next/head";
import { useEffect, useState } from "react";
import authProvider, {ADMIN_TOKEN} from "../../utils/admin/authProvider";
import {ENTRYPOINT} from "../../config/entrypoint";
import {
  fetchHydra as baseFetchHydra,
  hydraDataProvider as baseHydraDataProvider,
  useIntrospection,
} from "@api-platform/admin";
import {parseHydraDocumentation} from "@api-platform/api-doc-parser";
import { Navigate, Route } from "react-router-dom";
import { CustomRoutes } from "react-admin";

const getHeaders = (): HeadersInit => (localStorage.getItem(ADMIN_TOKEN) ? {
  Authorization: `Bearer ${localStorage.getItem(ADMIN_TOKEN)}`,
} : {});

const fetchHydra = (url: URL, options = {}) =>
  baseFetchHydra(url, {
    ...options,
    headers: getHeaders
  });

const RedirectToLogin = () => {
  const introspect = useIntrospection();
  if (localStorage.getItem(ADMIN_TOKEN)) {
    introspect();
    return <></>;
  }
  return <Navigate to="/login" />;
};

const apiDocumentationParser = (setRedirectToLogin: (arg0: boolean) => void) => async () => {
  try {
    setRedirectToLogin(false);
    // @ts-ignore
    return await parseHydraDocumentation(ENTRYPOINT, { headers: getHeaders });
  } catch (result) {
    // @ts-ignore
    const { api, response, status } = result;
    if (status !== 401 || !response) {
      throw result;
    }
    // Prevent infinite loop if the token is expired
    localStorage.removeItem(ADMIN_TOKEN);
    setRedirectToLogin(true);
    return {
      api,
      response,
      status,
    };
  }
};

const dataProvider = (setRedirectToLogin: (arg0: boolean) => void) => baseHydraDataProvider({
  // @ts-ignore
  entrypoint: ENTRYPOINT,
  httpClient: fetchHydra,
  apiDocumentationParser: apiDocumentationParser(setRedirectToLogin),
});

const Admin = () => {
  // Load the admin client-side
  const [DynamicAdmin, setDynamicAdmin] = useState(<p>Loading...</p>);
  const [redirectToLogin, setRedirectToLogin] = useState(false);
  useEffect(() => {
    (async () => {
      const HydraAdmin = (await import("@api-platform/admin")).HydraAdmin;

      setDynamicAdmin(<HydraAdmin
        dataProvider={dataProvider(setRedirectToLogin)}
        authProvider={authProvider}
        entrypoint={window.origin}
      >
        <CustomRoutes>
          {redirectToLogin ? <Route path="/" element={<RedirectToLogin />} /> : null}
        </CustomRoutes>
      </HydraAdmin>);
    })();
  }, []);

  return (
    <>
      <Head>
        <title>API Platform Admin</title>
      </Head>

      {DynamicAdmin}
    </>
  );
};
export default Admin;
