import { GetServerSidePropsContext } from 'next';
import {getServerSession} from "next-auth";
import {options} from "../pages/api/auth/[...nextauth]";

export const withApiToken = (
  func: (props: { apiToken: string, context: GetServerSidePropsContext }) => any
) => {
  return async (context: GetServerSidePropsContext) => {
    const session = (await getServerSession(
      context.req,
      context.res,
      options
    ));

    if (!session) {
      console.error('session empty')
      return {
        props: {},
      };
    }

    return await func({ apiToken: session.user.apiToken, context });
  };
};
