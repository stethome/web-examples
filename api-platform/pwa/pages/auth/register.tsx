import {NextComponentType, NextPageContext} from "next";
import Head from "next/head";
import RegisterForm from "../../components/app/RegisterForm";

const RegisterPage: NextComponentType<NextPageContext> = () => (
  <div>
    <div>
      <Head>
        <title>StethoMe Demo Platform - Register</title>
      </Head>
    </div>
    <main>
      <RegisterForm />
    </main>
  </div>
);

export default RegisterPage;
