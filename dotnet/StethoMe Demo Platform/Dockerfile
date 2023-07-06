FROM mcr.microsoft.com/dotnet/aspnet:7.0 AS base
WORKDIR /app
EXPOSE 80
EXPOSE 443

FROM mcr.microsoft.com/dotnet/sdk:7.0 AS build
WORKDIR /src
COPY ["StethoMe Demo Platform/StethoMe Demo Platform.csproj", "StethoMe Demo Platform/"]
RUN dotnet restore "StethoMe Demo Platform/StethoMe Demo Platform.csproj"
COPY . .
WORKDIR "/src/StethoMe Demo Platform"
RUN dotnet build "StethoMe Demo Platform.csproj" -c Release -o /app/build

FROM build AS publish
RUN dotnet publish "StethoMe Demo Platform.csproj" -c Release -o /app/publish

FROM base AS final
WORKDIR /app
COPY --from=publish /app/publish .
ENTRYPOINT ["dotnet", "StethoMe Demo Platform.dll"]
