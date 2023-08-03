import {fetch, FetchResponse} from "../../../utils/dataAccess";
import CredentialsProvider from "next-auth/providers/credentials"
import NextAuth, {AuthOptions, User} from "next-auth";
import {NextApiRequest, NextApiResponse} from "next";
import {LoginTokenResponse} from "../../../types/auth";
import jwtDecode from "jwt-decode";

export const options: AuthOptions = {
  session: { strategy: "jwt" },
  providers: [
    CredentialsProvider({
      name: 'Credentials',
      credentials: {
        email: { label: "Email", type: "text" },
        password: { label: "Password", type: "password" },
      },
      authorize: async (credentials) => {
        const { email, password } = credentials as { email: string, password: string };
        const response = await fetch<LoginTokenResponse>("/api/auth", {
          method: "POST",
          body: JSON.stringify({
            email,
            password,
          }),
          headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
          },
        });

        console.log("---------- authorize", email)

        const user = response as FetchResponse<LoginTokenResponse>;

        return {
          id: email,
          email,
          apiToken: user.data.token,
        } as User;
      },
    })
  ],
  callbacks: {
    async session({ session, token }) {
      if (token.user) {
        session.user = token.user;
      }

      return session
    },
    async jwt({ token, user, account }) {
      if (user) {
        token.user = user;
      }

      // apiToken is expired, force login
      const apiToken = jwtDecode(token.user.apiToken) as { exp: number };
      if (new Date() > new Date(apiToken.exp * 1000)) {
        console.error('api token is expired', apiToken)
        // @todo logout
      }

      return token
    },
  },
}

const auth = (req: NextApiRequest, res: NextApiResponse) => NextAuth(req, res, options);
export default auth;
