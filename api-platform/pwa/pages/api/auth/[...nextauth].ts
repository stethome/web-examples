import {fetch, FetchResponse} from "../../../utils/dataAccess";
import CredentialsProvider from "next-auth/providers/credentials"
import NextAuth, {AuthOptions, Session, User} from "next-auth";
import {JWT} from "next-auth/jwt";
import {NextApiRequest, NextApiResponse} from "next";
import {LoginCredentials, LoginTokenResponse} from "../../../types/auth";
import {CredentialInput} from "next-auth/src/providers/credentials";

const options: AuthOptions = {
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

        const user = response as FetchResponse<LoginTokenResponse>;

        return {
          id: email,
          jwt: user.data
        };
      },
    })
  ],
  // callbacks: {
  //   async jwt(token: JWT, user: User) {
  //     console.log('jwt', token, user);
  //     if (user) {
  //       token.user = user;
  //     }
  //
  //     // token is valid
  //     if (new Date() < new Date(token.user.exp)) {
  //       return token;
  //     }
  //
  //     // token has expired: force user to login again
  //     // todo implement refresh token
  //     return null;
  //   },
  //   async session(session: Session, token: JWT) {
  //     console.log('session', token, session);
  //     if (token) {
  //       session.token = token;
  //     }
  //     if (token.user) {
  //       session.user = token.user;
  //     }
  //
  //     return session;
  //   },
  // },
}

const auth = (req: NextApiRequest, res: NextApiResponse) => NextAuth(req, res, options);
export default auth;
