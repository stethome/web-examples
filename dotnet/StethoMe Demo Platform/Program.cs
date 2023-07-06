using Microsoft.AspNetCore.Components;
using Microsoft.AspNetCore.Components.Web;
using StethoMe_Demo_Platform.Data;
using StethoMe_Demo_Platform.Data.StethoMe;

var builder = WebApplication.CreateBuilder(args);

builder.Host.ConfigureAppConfiguration((a, config) =>
{
    config.AddJsonFile(
        "appsettings.Local.json",
        optional: true,
        reloadOnChange: true);
});

// Add services to the container.
builder.Services.AddRazorPages();
builder.Services.AddServerSideBlazor();
builder.Services.AddSingleton<WeatherForecastService>();

builder.Services.AddHttpClient("mediaApiClient", c => c.BaseAddress = new Uri("https://dev.media-api.stethome.com"));
builder.Services.AddSingleton<MediaApiService>();

var app = builder.Build();

// Configure the HTTP request pipeline.
if (!app.Environment.IsDevelopment())
{
    app.UseExceptionHandler("/Error");
    // The default HSTS value is 30 days. You may want to change this for production scenarios, see https://aka.ms/aspnetcore-hsts.
    app.UseHsts();
}

app.UseHttpsRedirection();

app.UseStaticFiles();

app.UseRouting();

app.MapBlazorHub();
app.MapFallbackToPage("/_Host");

app.Run();