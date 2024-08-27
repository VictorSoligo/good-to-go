// components
import React from "react";
import { AuthContext } from "./auth-context";
import { Loading } from "@/src/components/loading";
//

// ----------------------------------------------------------------------

type Props = {
  children: React.ReactNode;
};

export function AuthConsumer({ children }: Props) {
  return (
    <AuthContext.Consumer>
      {(auth) => (auth.loading ? <Loading /> : children)}
    </AuthContext.Consumer>
  );
}
