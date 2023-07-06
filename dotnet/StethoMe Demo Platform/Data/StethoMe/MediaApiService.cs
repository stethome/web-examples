using System.Net.Http.Headers;
using System.Text.Json.Serialization;

namespace StethoMe_Demo_Platform.Data.StethoMe;

public class MediaApiService
{
    public class MediaApiOptions
    {
        public string VendorToken { get; set; } = String.Empty;
    }

    public struct MediaApiClientTokenResponse
    {
        [JsonPropertyName("token")]
        public string Token { get; set; }
    }

    private readonly MediaApiOptions _options;
    private readonly HttpClient _httpClient;

    public MediaApiService(IHttpClientFactory httpClientFactory, IConfiguration configuration)
    {
        var options = new MediaApiOptions();
        configuration.GetSection("MediaApi").Bind(options);
        _options = options;
        _httpClient = httpClientFactory.CreateClient("mediaApiClient");
    }

    public async Task<MediaApiClientTokenResponse> GetClientToken()
    {
        using var requestMessage = new HttpRequestMessage(HttpMethod.Get, "/v2/security/token");
        requestMessage.Headers.Authorization = 
            new AuthenticationHeaderValue("Bearer", _options.VendorToken);

        var resp = await _httpClient.SendAsync(requestMessage);
        resp.EnsureSuccessStatusCode();

        return await resp.Content.ReadFromJsonAsync<MediaApiClientTokenResponse>();
    }
}