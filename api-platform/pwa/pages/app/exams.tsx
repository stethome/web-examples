import {NextComponentType, NextPageContext} from "next";
import Head from "next/head";

const ExamsPage: NextComponentType<NextPageContext> = () => (
  <div>
    <div>
      <Head>
        <title>StethoMe Demo Platform</title>
      </Head>
    </div>
    <main>
      Exams Page
    </main>
  </div>
);

export default ExamsPage;
