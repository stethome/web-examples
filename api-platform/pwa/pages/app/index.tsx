import {NextComponentType, NextPageContext} from "next";
import Head from "next/head";

const Page: NextComponentType<NextPageContext> = () => (
  <div>
    <div>
      <Head>
        <title>StethoMe Demo Platform</title>
      </Head>
    </div>
    <main>
      Page
    </main>
  </div>
);

export default Page;
