import {NextComponentType, NextPageContext} from "next";
import Head from "next/head";
import RegisterForm from "../../components/app/RegisterForm";

const LoginPage: NextComponentType<NextPageContext> = () => (
  <div>
    <div>
      <Head>
        <title>StethoMe Demo Platform - Login</title>
      </Head>
    </div>
    <main>
      Login
    </main>
  </div>
);

export default LoginPage;
