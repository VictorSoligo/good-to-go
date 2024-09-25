import React from "react";
import { useEffect, useReducer, useCallback, useMemo, useState } from "react";
//
import { AuthContext } from "./auth-context";
import { ActionMapType, AuthStateType, AuthUserType } from "./types";
import { getSession, storageSession } from "../../storage/storageAuthToken";
import { removeSession, setAxiosSession } from "../../storage/axiosSession";
import authRepository from "../../repositories/auth-repository";
import { IAccount } from "../../types/account";
import { RegisterType } from "@/src/types/auth";

enum Types {
  INITIAL = "INITIAL",
  LOGIN = "LOGIN",
  LOGOUT = "LOGOUT",
  UPDATE_USER = "UPDATE_USER",
}

type Payload = {
  [Types.INITIAL]: {
    account: AuthUserType;
  };
  [Types.LOGIN]: {
    account: AuthUserType;
  };
  [Types.UPDATE_USER]: {
    account: AuthUserType;
  };
  [Types.LOGOUT]: undefined;
};

type ActionsType = ActionMapType<Payload>[keyof ActionMapType<Payload>];

// ----------------------------------------------------------------------

const initialState: AuthStateType = {
  account: null,
  loading: true,
};

const reducer = (state: AuthStateType, action: ActionsType) => {
  if (action.type === Types.INITIAL) {
    return {
      loading: false,
      account: action.payload.account,
    };
  }
  if (action.type === Types.LOGIN) {
    return {
      ...state,
      account: action.payload.account,
    };
  }
  if (action.type === Types.UPDATE_USER) {
    return {
      ...state,
      account: action.payload.account,
    };
  }
  if (action.type === Types.LOGOUT) {
    return {
      ...state,
      account: null,
    };
  }
  return state;
};

// ----------------------------------------------------------------------

type Props = {
  children: React.ReactNode;
};

export function AuthProvider({ children }: Props) {
  const [isLoadingAccount, setIsLoadingAccount] = useState(false);
  const [state, dispatch] = useReducer(reducer, initialState);

  const initialize = async () => {
    try {
      const { token } = await getSession();

      if (token) {
        setAxiosSession(token);

        const account = await authRepository.myAccount();

        dispatch({
          type: Types.INITIAL,
          payload: {
            account,
          },
        });
      } else {
        await removeSession();

        dispatch({
          type: Types.INITIAL,
          payload: {
            account: null,
          },
        });
      }
    } catch (error) {
      await removeSession();

      dispatch({
        type: Types.INITIAL,
        payload: {
          account: null,
        },
      });
    }
  };

  useEffect(() => {
    initialize();
  }, []);

  // LOGIN
  const login = useCallback(async (email: string, password: string) => {
    try {
      setIsLoadingAccount(true);

      const { accessToken } = await authRepository.login({ email, password });

      await storageSession({
        token: accessToken,
      });

      setAxiosSession(accessToken);

      const account = await authRepository.myAccount();

      dispatch({
        type: Types.LOGIN,
        payload: {
          account,
        },
      });
    } catch (error) {
      throw error;
    } finally {
      setIsLoadingAccount(false);
    }
  }, []);

  // REGISTER
  const register = useCallback(async (register: RegisterType) => {
    try {
      setIsLoadingAccount(true);

      await authRepository.register(register);

      setIsLoadingAccount(false);
    } catch (error) {
      throw error;
    } finally {
      setIsLoadingAccount(false);
    }
  }, []);

  // LOGOUT
  const logout = useCallback(async () => {
    await removeSession();

    dispatch({
      type: Types.LOGOUT,
    });
  }, []);

  // ----------------------------------------------------------------------

  const checkAuthenticated = state.account
    ? "authenticated"
    : "unauthenticated";

  const status = state.loading ? "loading" : checkAuthenticated;

  const memoizedValue = useMemo(
    () => ({
      isLoadingAccount,
      account: state.account,
      loading: status === "loading",
      authenticated: status === "authenticated",
      unauthenticated: status === "unauthenticated",
      login,
      logout,
      register,
    }),
    [login, logout, state.account, status, isLoadingAccount, register]
  );

  return (
    <AuthContext.Provider value={memoizedValue}>
      {children}
    </AuthContext.Provider>
  );
}
